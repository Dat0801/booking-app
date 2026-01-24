<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'type',
        'name',
        'slug',
        'description',
        'price',
        'duration_minutes',
        'stock_quantity',
        'is_active',
        'image_url',
        'location',
        'rating',
        'review_count',
        'gallery',
        'amenities',
        'reviews',
    ];

    protected $casts = [
        'gallery' => 'array',
        'amenities' => 'array',
        'reviews' => 'array',
        'rating' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
