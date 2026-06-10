<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Wishlist;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass Assignable
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // ✅ IMPORTANT (admin system ke liye)
    ];

    /**
     * Hidden Fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =====================
    // Relationships
    // =====================

    // 🛒 Cart Items
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // ❤️ Wishlist Items
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    // 📦 Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // =====================
    // Helper: Cart Count
    // =====================
    public function getCartCountAttribute()
    {
        return $this->cartItems()->count();
    }

    // =====================
    // Helper: Wishlist Count (NEW 🔥)
    // =====================
    public function getWishlistCountAttribute()
    {
        return $this->wishlistItems()->count();
    }


    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}