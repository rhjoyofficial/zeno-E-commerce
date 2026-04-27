<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ProductReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'rating',
        'customer_id',
        'product_id',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'status' => 'string',
        'rating' => 'integer',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    protected static function booted(): void
    {
        static::creating(function ($review) {
            $review->created_by = Auth::id();
            $review->updated_by = Auth::id();
        });
        static::updating(function ($review) {
            $review->updated_by = Auth::id();
        });
    }
}
