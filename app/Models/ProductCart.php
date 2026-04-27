<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'color',
        'size',
        'qty',
        'price',
        'cart_key',
        // 'created_by',
        // 'updated_by',
    ];
    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function activeProduct()
    {
        return $this->belongsTo(Product::class)->where('status', 'active');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    public function activeVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id')->where('status', 'active');
    }

    protected static function booted()
    {
        static::saving(function ($cart) {
            $cart->cart_key = implode(':', [
                $cart->user_id,
                $cart->product_id,
                $cart->variant_id ?: 'base',
            ]);
        });
    }

}
