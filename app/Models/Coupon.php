<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
   protected $fillable = [
    'code', 
    'discount_percent', 
    'max_discount',
    'is_active', 
    'usage_limit',
    'times_used' 
    ];
}