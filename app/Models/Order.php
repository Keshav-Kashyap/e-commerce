<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    // =====================
    // Mass Assignment
    // =====================
    protected $fillable = [
    'user_id', 
    'name', 
    'email', 
    'phone', 
    'address', 
    'pincode', 
    'city',    
    'state',   
    'subtotal', 
    'coupon_code', 
    'discount_amount', 
    'total_amount', 
    'status',
    'cancel_reason',
    'tracking_id',
    'shiprocket_order_id',
    'shiprocket_shipment_id',
    'shiprocket_awb',
    'shiprocket_status',
    'shiprocket_response',
    'shiprocket_sync_error',
    'shiprocket_synced_at',
    'gst_amount',
    'shipping_amount',
    'payment_method'
];

    // =====================
    // Relationships
    // =====================

    // 👉 Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 👉 Order has many Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // =====================
    // Helper: Total Quantity (optional)
    // =====================
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }
}