<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Cart;

class Product extends Model
{
    use HasFactory;

    // =====================
    // Mass Assignment
    // =====================
    protected $fillable = ['name', 'description', 'price', 'stock', 'category', 'image', 'gallery'];

    // =====================
    // Relationships
    // =====================

    // 👉 Product in many Cart items
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // 👉 Product in many Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // =====================
    // Helper: Image URL
    // =====================
    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset($this->image) 
            : asset('images/default.png');
    }

    // =====================
    // Helper: Stock Status
    // =====================
    public function getStockStatusAttribute()
    {
        return $this->stock > 0 ? 'In Stock' : 'Out of Stock';
    }

    /**
     * 🔥 Get the reviews for the product.
     */
    public function reviews()
    {
        // Ek product ke bahut saare reviews ho sakte hain (One-to-Many)
        return $this->hasMany(Review::class);
    }
}