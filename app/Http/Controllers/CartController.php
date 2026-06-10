<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 🔥 COMMON AUTH CHECK
    private function checkAuth()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'login_required'
            ], 401);
        }
        return null;
    }

    // ================= SHOW CART =================
    public function index()
    {
        if ($res = $this->checkAuth()) return $res;

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    // ================= ADD TO CART (UPDATED FOR SPECIFIC QTY) =================
    public function add(Request $request, $id)
    {
        // 🔐 AUTH CHECK
        if ($res = $this->checkAuth()) return $res;

        // 🔍 FIND PRODUCT
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        // 🔥 GET QUANTITY FROM REQUEST (Default is 1 if not provided)
        $requestedQty = (int) $request->input('quantity', 1);

        // ❌ STOCK CHECK (Initial check)
        if ($product->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Out of stock'
            ], 400);
        }

        // 🔍 CHECK EXISTING CART ITEM
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $newQty = $cart->quantity + $requestedQty;

            // 🔥 LIMIT CHECK AGAINST STOCK
            if ($newQty > $product->stock) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Only {$product->stock} units available. You already have some in cart."
                ], 400);
            }

            $cart->update(['quantity' => $newQty]);

        } else {
            // Check if requested qty is within stock
            if ($requestedQty > $product->stock) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Requested quantity exceeds available stock ({$product->stock})"
                ], 400);
            }

            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => $requestedQty
            ]);
        }

        // Return updated cart count for the badge
        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'status' => 'success',
            'message' => 'Added to cart',
            'cart_count' => $cartCount
        ]);
    }

    // ================= INCREASE =================
    public function increase($id)
    {
        if ($res = $this->checkAuth()) return $res;

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        $product = $cart->product;

        if ($cart->quantity >= $product->stock) {
            return response()->json(['status' => 'error', 'message' => 'Stock limit reached']);
        }

        $cart->increment('quantity');
        return response()->json(['status' => 'success']);
    }

    // ================= DECREASE =================
    public function decrease($id)
    {
        if ($res = $this->checkAuth()) return $res;

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        if ($cart->quantity > 1) {
            $cart->decrement('quantity');
        } else {
            $cart->delete();
        }

        return response()->json(['status' => 'success']);
    }

    // ================= REMOVE =================
    public function remove($id)
    {
        if ($res = $this->checkAuth()) return $res;

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed'
        ]);
    }
}