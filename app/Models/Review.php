<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    // 🔥 Yeh line add kijiye
    protected $fillable = ['user_id', 'product_id', 'comment', 'rating'];

    // User ke saath relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Product ke saath relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}   