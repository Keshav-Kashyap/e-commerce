<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;

class OrderItem extends Model
{
    use HasFactory;

    // =====================
    // Mass Assignment
    // =====================
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // =====================
    // Relationships
    // =====================

    // 👉 OrderItem belongs to Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 👉 OrderItem belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // =====================
    // Helper: Total Price
    // =====================
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    // =====================
    // Helper: Product Name (Safe)
    // =====================
    public function getProductNameAttribute()
    {
        return $this->product ? $this->product->name : 'Product Deleted';
    }
}