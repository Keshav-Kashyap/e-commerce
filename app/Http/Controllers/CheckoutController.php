<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Setting; // 🔥 Shipping charge fetch karne ke liye import kiya

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $product = null;
        $cartItems = collect();
        $totalAmount = 0;

        // Case 1: Direct "Buy Now" (Single Product)
        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            if ($product->stock < 1) {
                return redirect()->back()->with('error', 'Maaf kijiye, yeh product abhi Out of Stock hai!');
            }
            $qty = $request->quantity ?? 1;
            $totalAmount = $product->price * $qty;
        } 
        // Case 2: From Cart
        else {
            $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
            if ($cartItems->isEmpty()) {
                return redirect()->route('home')->with('error', 'Aapka cart khali hai!');
            }
            foreach($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    return redirect()->route('cart.index')->with('error', $item->product->name . ' ka utna stock available nahi hai. Kripya quantity kam karein.');
                }
                $totalAmount += ($item->product->price * $item->quantity);
            }
        }

        // 🔥 COUPON DISCOUNT CALCULATION
        $discount = 0;
        if (session()->has('coupon')) {
            $discount = ($totalAmount * session('coupon')['discount_percent']) / 100;
        }
        
        // 🔥 NAYA: GST (3%) & SHIPPING CALCULATION
        $gstAmount = ($totalAmount - $discount) * 0.03; 
        $shippingCharge = Setting::where('key', 'shipping_charge')->value('value') ?? 0;

        // 🔥 NAYA: Grand Total Update
        $grandTotal = ($totalAmount - $discount) + $gstAmount + $shippingCharge;

        // Variables view mein bhej diye
        return view('checkout', compact('product', 'cartItems', 'totalAmount', 'discount', 'gstAmount', 'shippingCharge', 'grandTotal'));
    }

    // 🔥 Apply Coupon Function
    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        
        // Database mein active coupon dhundho
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->where('is_active', true)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or expired coupon code!');
        }

        // Check Global Limit
        if ($coupon->usage_limit !== null && $coupon->times_used >= $coupon->usage_limit) {
            return back()->with('error', 'Yeh coupon apni limit cross kar chuka hai aur ab valid nahi hai!');
        }

        // Check User per Profile Limit
        $alreadyUsed = \App\Models\Order::where('user_id', auth()->id())
                        ->where('coupon_code', $coupon->code)
                        ->where('status', '!=', 'cancelled') 
                        ->exists();

        if ($alreadyUsed) {
            return back()->with('error', 'Coupon already redeemed! Aap ise ek baar use kar chuke hain.');
        }

        // Session mein save kar do
        session()->put('coupon', [
            'code' => $coupon->code,
            'discount_percent' => $coupon->discount_percent
        ]);

        return back()->with('success', 'Coupon applied! You got ' . $coupon->discount_percent . '% OFF.');
    }

    // 🔥 Remove Coupon Function
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed successfully!');
    }
}