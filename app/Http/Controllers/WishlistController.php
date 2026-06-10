<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * ADD / REMOVE wishlist
     */
    public function toggle($id)
    {
        // ❌ Not logged in
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login required'
            ], 401);
        }

        try {
            $userId = Auth::id();

            // 🔍 Check existing
            $wishlist = Wishlist::where('user_id', $userId)
                ->where('product_id', $id)
                ->first();

            // ❌ Remove
            if ($wishlist) {
                $wishlist->delete();

                return response()->json([
                    'status' => 'removed'
                ]);
            }

            // ✅ Add safely
            Wishlist::firstOrCreate([
                'user_id' => $userId,
                'product_id' => $id
            ]);

            return response()->json([
                'status' => 'added'
            ]);

        } catch (\Exception $e) {

            // 🔴 DEBUG (optional)
            \Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * SHOW wishlist page
     */
    public function index()
    {
        // ❌ If not logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $items = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist', compact('items'));
    }
    
}