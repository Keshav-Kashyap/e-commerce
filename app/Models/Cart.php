<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Cart extends Model
{
    use HasFactory;

    // =====================
    // Mass Assignment
    // =====================
    protected $fillable = [
        'user_id',     // 🔥 added
        'product_id',
        'quantity'
    ];

    // =====================
    // Relationships
    // =====================

    // 👉 Cart belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 👉 Cart belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // =====================
    // Helper: Total Price
    // =====================
    public function getTotalPriceAttribute()
    {
        // 🔐 Safe check (avoid error if product deleted)
        return ($this->product ? $this->product->price : 0) * $this->quantity;
    }
}