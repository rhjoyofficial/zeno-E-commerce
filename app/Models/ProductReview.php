<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProductReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'rating',
        'customer_id',
        'product_id',
        'status',
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
}
