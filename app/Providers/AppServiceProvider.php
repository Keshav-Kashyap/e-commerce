<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; /* 🔥 NAYI LINE ADD KI HAI HTTPS KE LIYE */
use App\Models\Cart;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /* =========================================================
           🔥 NAYA CODE: Live server par HTTPS force karne ke liye
           ========================================================= */
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }


        /* =========================================================
           🛒 PURANA CODE: Cart aur Wishlist Data Global bhejne ke liye 
           (Ye bilkul waisa hi hai jaisa aapka tha)
           ========================================================= */
        View::composer(['layouts.app'], function ($view) {

            $cartCount = 0;
            $wishlistCount = 0;
            $cartItems = collect();

            if (Auth::check()) {

                $user = Auth::user();

                // 🔥 STEP 3 FINAL FIX (IMPORTANT)
                $cartItems = Cart::with(['product' => function ($q) {
                    $q->select('id', 'name', 'price', 'stock', 'image');
                }])
                ->where('user_id', $user->id)
                ->get();

                // 🔥 SAFE COUNT (null product handle)
                $cartCount = $cartItems->count();

                // 🔥 SAFE wishlist count
                $wishlistCount = method_exists($user, 'wishlistItems') 
                    ? $user->wishlistItems()->count() 
                    : 0;
            }

            $view->with([
                'cartCount' => $cartCount,
                'wishlistCount' => $wishlistCount,
                'cartItems' => $cartItems
            ]);
        });
    }
}