<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController, 
    CartController, 
    OrderController, 
    AuthController, 
    WishlistController, 
    ForgotPasswordController, 
    CheckoutController,
    AdminController 
};

/* --- PUBLIC ROUTES --- */
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{name}', [ProductController::class, 'category'])->name('category');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/live-search', [ProductController::class, 'liveSearch'])->name('live.search');
Route::get('/price/{amount}', [ProductController::class, 'priceFilter'])->name('price.filter');
Route::post('/product/review', [OrderController::class, 'storeReview'])->name('product.review')->middleware('auth');

/* --- AUTH ROUTES --- */
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* --- FORGOT PASSWORD --- */
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.send');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

/* --- PROTECTED USER ROUTES --- */
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist-toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply.coupon');
    Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove.coupon');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
    Route::get('/razorpay-payment', [OrderController::class, 'razorpayPayment'])->name('razorpay.payment');

    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::post('/order/cancel-customer/{id}', [OrderController::class, 'customerCancelOrder'])->name('order.cancel.customer');

    Route::post('/order/return/{id}', [OrderController::class, 'requestReturn'])->name('order.return');
    Route::get('/profile', [OrderController::class, 'myOrders'])->name('profile')->middleware('auth');
    Route::post('/profile/update', [AuthController::class, 'update'])->name('profile.update');

    Route::get('/order/invoice/{id}', [OrderController::class, 'downloadInvoice'])->name('order.invoice');
});

/* --- ADMIN ROUTES --- */
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    Route::get('/sales-invoices', [AdminController::class, 'salesInvoices'])->name('admin.invoices');
    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // Products Management
    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::match(['put','post'], '/products/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    // Orders Management (Filtered Lists)
    Route::get('/orders', [OrderController::class, 'adminOrders'])->name('admin.orders');
    Route::post('/orders/update/{id}', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
    Route::post('/order/cancel/{id}', [OrderController::class, 'cancelOrder'])->name('admin.order.cancel');
    
    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

    // Inventory, Customers, Reviews
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::delete('/reviews/{id}', [AdminController::class, 'destroyReview'])->name('admin.reviews.destroy');

    // Coupons
    Route::get('/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
    Route::post('/coupons/toggle/{id}', [AdminController::class, 'toggleCoupon'])->name('admin.coupons.toggle');
    Route::delete('/coupons/{id}', [AdminController::class, 'destroyCoupon'])->name('admin.coupons.destroy');
    Route::put('/coupons/{id}', [AdminController::class, 'updateCoupon'])->name('admin.coupons.update');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

/* --- PAYMENT & LEGAL --- */
Route::post('/payment-success', [OrderController::class, 'paymentSuccess'])->name('payment.success')->middleware('auth');

Route::get('/order-success/{order_id}', function ($order_id) {
    $order = \App\Models\Order::findOrFail($order_id);
    return view('success', compact('order'));
})->name('order.success');

Route::get('/terms-and-conditions', function () { return view('legal.terms'); })->name('terms');
Route::get('/privacy-policy', function () { return view('legal.privacy'); })->name('privacy');
Route::get('/refund-policy', function () { return view('legal.refund'); })->name('refund');
Route::get('/shipping-policy', function () { return view('legal.shipping'); })->name('shipping');

/* --- CONTACT US --- */
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::post('/contact/send', function (\Illuminate\Http\Request $request) {
    $request->validate(['name' => 'required', 'email' => 'required|email', 'subject' => 'required', 'message' => 'required']);
    try {
        \Illuminate\Support\Facades\Mail::raw("Inquiry from {$request->name}: {$request->message}", function($msg) use ($request) {
            $msg->to('support@shringar.net')->subject($request->subject);
        });
        return back()->with('success', 'Aapka message bhej diya gaya hai!');
    } catch (\Exception $e) { 
        return back()->with('success', 'Aapka message record ho gaya hai!'); 
    }
})->name('contact.send');