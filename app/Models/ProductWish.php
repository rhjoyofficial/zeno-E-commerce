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
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::saving(function ($wish) {
            $wish->wish_key = $wish->user_id . ':' . $wish->product_id;
        });
    }
}
