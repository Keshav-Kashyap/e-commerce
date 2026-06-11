<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Review;
use App\Services\ShiprocketService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
// ================= PLACE ORDER (COD & RAZORPAY) =================
    public function placeOrder(Request $request)
    {
        $cartItems = collect();
        $total = 0;

        // 1. Buy Now vs Cart Logic
        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $qty = $request->quantity ?? 1;
            $total = $product->price * $qty;

            $item = new \stdClass();
            $item->product_id = $product->id;
            $item->quantity = $qty;
            $item->product = $product;
            $cartItems->push($item);
        } else {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty!');
            }
            foreach ($cartItems as $item) {
                if (!$item->product) continue;
                $total += $item->product->price * $item->quantity;
            }
        }

        $discount = 0;
        $couponCode = null;
        if (session()->has('coupon')) {
            $couponCode = session('coupon')['code'];
            $discount = ($total * session('coupon')['discount_percent']) / 100;
        }

        $shippingCharge = Setting::where('key', 'shipping_charge')->value('value') ?? 0;
        
        // 🔥 GST CALCULATION (3% on discounted amount)
        $gstAmount = ($total - $discount) * 0.03;
        
        // Final Total includes GST
        $finalTotal = ($total - $discount) + $gstAmount + $shippingCharge;

        $deliveryDays = (strtolower($request->city) == 'agra') ? 3 : 7;
        $estimatedDate = now()->addDays($deliveryDays);

        // CASE 1: CASH ON DELIVERY (COD)
        if ($request->payment_method == 'cod') {
            foreach ($cartItems as $item) {
                if (!$item->product || $item->quantity > $item->product->stock) {
                    return back()->with('error', $item->product->name . ' is out of stock!');
                }
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'pincode' => $request->pincode, 
                'city' => $request->city,       
                'state' => $request->state,     
                'subtotal' => $total,
                'coupon_code' => $couponCode,
                'discount_amount' => $discount,
                'gst_amount' => $gstAmount, // 🔥 Saving GST Amount
                'shipping_amount' => $shippingCharge,
                'total_amount' => $finalTotal,
                'status' => 'pending',
                'payment_method' => 'cod',
                'estimated_delivery' => $estimatedDate,
            ]);

            $savedItemCount = $this->saveOrderItems($order, $cartItems);

            Log::info('COD order items saved.', [
                'order_id' => $order->id,
                'item_count' => $savedItemCount,
            ]);

            $this->syncOrderToShiprocket($order->fresh(['items.product']));

            if (!$request->has('product_id')) { 
                Cart::where('user_id', Auth::id())->delete(); 
            }

            $this->handleCouponUsage($couponCode);
            session()->forget('coupon');

            return redirect()->route('order.success', ['order_id' => $order->id])->with('success', 'Order placed successfully!');
        }

        // CASE 2: RAZORPAY
        if ($request->payment_method == 'razorpay') {
            session()->put('razorpay_checkout_items', $cartItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price ?? $item->price ?? 0,
                ];
            })->values()->all());

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $razorOrder = $api->order->create([
                'receipt' => 'order_' . time(),
                'amount' => round($finalTotal * 100),
                'currency' => 'INR'
            ]);

            return view('razorpay-payment', [
                'razorOrder' => $razorOrder,
                'total' => $finalTotal,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'pincode' => $request->pincode, 
                'city' => $request->city,       
                'state' => $request->state,
                'product_id' => $request->product_id ?? null,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        return back()->with('error', 'Invalid payment method');
    }

    // ================= PAYMENT SUCCESS (RAZORPAY) =================
    public function paymentSuccess(Request $request)
    {
        $cartItems = collect();
        $total = 0;
        $checkoutItems = session('razorpay_checkout_items', []);

        if (! empty($checkoutItems)) {
            $cartItems = collect($checkoutItems)->map(function ($item) {
                $product = Product::find($item['product_id']);

                $cartItem = new \stdClass();
                $cartItem->product_id = $item['product_id'];
                $cartItem->quantity = (int) $item['quantity'];
                $cartItem->price = (float) ($item['price'] ?? 0);
                $cartItem->product = $product;

                return $cartItem;
            });

            foreach ($cartItems as $item) {
                $total += (float) ($item->price ?: ($item->product->price ?? 0)) * $item->quantity;
            }
        } elseif ($request->has('product_id') && $request->product_id != null) {
            $product = Product::findOrFail($request->product_id);
            $qty = $request->quantity ?? 1;
            $total = $product->price * $qty;
            $item = new \stdClass(); $item->product_id = $product->id; $item->quantity = $qty; $item->product = $product;
            $cartItems->push($item);
        } else {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
            foreach ($cartItems as $item) { $total += $item->product->price * $item->quantity; }
        }

        $discount = 0; $couponCode = null;
        if (session()->has('coupon')) {
            $couponCode = session('coupon')['code'];
            $discount = ($total * session('coupon')['discount_percent']) / 100;
        }

        $shippingCharge = Setting::where('key', 'shipping_charge')->value('value') ?? 0;
        $deliveryDays = (strtolower($request->city) == 'agra') ? 3 : 7;
        
        // 🔥 GST CALCULATION (3% on discounted amount)
        $gstAmount = ($total - $discount) * 0.03;

        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name, 'email' => $request->email, 'phone' => $request->phone,
            'address' => $request->address, 'pincode' => $request->pincode, 
            'city' => $request->city, 'state' => $request->state,
            'subtotal' => $total, 'coupon_code' => $couponCode, 'discount_amount' => $discount, 
            'gst_amount' => $gstAmount, // 🔥 Saving GST Amount
            'shipping_amount' => $shippingCharge,
            'estimated_delivery' => now()->addDays($deliveryDays),
            'total_amount' => ($total - $discount) + $gstAmount + $shippingCharge, // Final total with GST
            'status' => 'confirmed', 'payment_method' => 'razorpay',
        ]);

        Log::info('Razorpay payment success payload prepared.', [
            'order_id' => $order->id,
            'request_product_id' => $request->product_id,
            'request_quantity' => $request->quantity,
            'cart_item_count' => $cartItems->count(),
            'cart_product_ids' => $cartItems->pluck('product_id')->values()->all(),
            'user_id' => Auth::id(),
        ]);

        $savedItemCount = $this->saveOrderItems($order, $cartItems);

        Log::info('Razorpay order items saved.', [
            'order_id' => $order->id,
            'item_count' => $savedItemCount,
        ]);

        $this->syncOrderToShiprocket($order->fresh(['items.product']));
        session()->forget('razorpay_checkout_items');
        if (!$request->has('product_id')) { Cart::where('user_id', Auth::id())->delete(); }
        $this->handleCouponUsage($couponCode);
        session()->forget('coupon');

        return redirect()->route('order.success', ['order_id' => $order->id])->with('success', 'Payment successful!');
    }

    private function handleCouponUsage($couponCode) {
        if ($couponCode) {
            $usedCoupon = Coupon::where('code', $couponCode)->first();
            if ($usedCoupon) {
                $usedCoupon->increment('times_used');
                if ($usedCoupon->usage_limit !== null && $usedCoupon->times_used >= $usedCoupon->usage_limit) {
                    $usedCoupon->update(['is_active' => false]);
                }
            }
        }
    }

    private function saveOrderItems($order, $items): int {
        $savedCount = 0;

        foreach ($items as $item) {
            $product = $item->product ?? Product::find($item->product_id);

            if (! $product) {
                Log::warning('Order item skipped because product was missing.', [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                ]);

                continue;
            }

            $product->stock = max(0, $product->stock - $item->quantity);
            $product->save();

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'price' => $product->price,
            ]);

            $savedCount++;
        }

        return $savedCount;
    }

    private function syncOrderToShiprocket(Order $order): void
    {
        try {
            $order->loadMissing('items.product');

            if ($order->items->isEmpty()) {
                Log::warning('Shiprocket sync skipped: order has no items.', [
                    'order_id' => $order->id,
                ]);

                $order->update(['shiprocket_sync_error' => 'Order has no items to sync.']);

                return;
            }

            $result = app(ShiprocketService::class)->syncOrder($order);

            if (! $result['success']) {
                $order->update(['shiprocket_sync_error' => $result['message']]);
                Log::warning('Shiprocket sync skipped or failed.', [
                    'order_id' => $order->id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Throwable $e) {
            $order->update(['shiprocket_sync_error' => $e->getMessage()]);
            Log::error('Shiprocket sync exception.', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // ================= CUSTOMER PANEL FUNCTIONS =================
    
public function myOrders() {
    $uid = Auth::id();
    
    // Overview aur count ke liye
    $allOrders = Order::where('user_id', $uid)->latest()->get();

    // Filters ke liye alag variables
    $activeOrders = Order::where('user_id', $uid)->whereIn('status', ['pending', 'confirmed', 'shipped'])->latest()->get();
    $pastOrders = Order::where('user_id', $uid)->where('status', 'delivered')->latest()->get();
    $returnRequests = Order::where('user_id', $uid)->where('status', 'return_requested')->latest()->get();
    $cancelledOrders = Order::where('user_id', $uid)->whereIn('status', ['cancelled', 'rejected'])->latest()->get();

    return view('auth.profile', compact('allOrders', 'activeOrders', 'pastOrders', 'returnRequests', 'cancelledOrders'));
}

    public function customerCancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Reason nikalna
        $reason = $request->reason;
        if ($reason === 'Other') {
            $reason = $request->other_reason;
        }

        // Database mein update karna
        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $reason
        ]);

        return back()->with('success', 'Order cancelled successfully.');
    }
    // ================= ADMIN FUNCTIONS =================

    public function adminOrders() {
        return view('admin.orders', [
            'activeOrders' => Order::with('user')->whereIn('status', ['pending', 'confirmed', 'shipped'])->latest()->get(),
            'completeOrders' => Order::with('user')->where('status', 'delivered')->latest()->get(),
            'returnRequests' => Order::with('user')->where('status', 'return_requested')->latest()->get(),
            'rejectedOrders' => Order::with('user')->whereIn('status', ['cancelled', 'rejected'])->latest()->get(),
        ]);
    }

    public function updateStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status, 
            'tracking_id' => $request->tracking_id
        ]);
        return back()->with('success', 'Order updated successfully!');
    }

    public function cancelOrder(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => 'cancelled', 
            'cancel_reason' => $request->reason
        ]);
        return back()->with('success', 'Order cancelled by Admin!');
    }

    public function storeReview(Request $request) {
        Review::create([
            'user_id' => auth()->id(), 
            'product_id' => $request->product_id,
            'comment' => $request->comment, 
            'rating' => $request->rating,
        ]);
        return back()->with('success', 'Thank you for your feedback!');
    }

public function requestReturn(Request $request, $id)
    {
        // Order find karo jo sirf logged-in user ka ho
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Date check (dobara backend me check karna zaroori hai security ke liye)
        $isWithin7Days = \Carbon\Carbon::parse($order->created_at)->addDays(7)->isFuture();

        if ($order->status === 'delivered' && $isWithin7Days) {
            
            // Order ka status update kar do
            $order->update([
                'status' => 'return_requested' // Admin ab isko panel me dekh payega
            ]);

            return back()->with('success', 'Return request submitted successfully. Our team will contact you soon.');
        }

        return back()->with('error', 'Sorry, this order is no longer eligible for a return.');
    }

    public function downloadInvoice($id) 
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        
        // Security check: Only admin or the customer who placed the order can download it
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $pdf = Pdf::loadView('invoice_pdf', compact('order'));
        return $pdf->download('Shringar_Invoice_#'.$order->id.'.pdf');
    }

}