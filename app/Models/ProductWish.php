<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductWish extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'wish_key',
        // 'variant_id', // Optional: Add if schema updated
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Optional: Uncomment if adding variant_id to schema
    // public function productVariant()
    // {
    //     return $this->belongsTo(ProductVariant::class, 'variant_id');
    // }

    protected static function booted()
    {
        static::saving(function ($wish) {
            $wish->wish_key = $wish->user_id . ':' . $wish->product_id;
        });
    }
}
