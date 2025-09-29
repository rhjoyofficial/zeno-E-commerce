<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'name',
        'sku',
        'description',
        'variant_size',
        'variant_color',
        'price',
        'original_price',
        'discount_amount',
        'tax_amount',
        'quantity',
        'quantity_shipped',
        'quantity_refunded',
        'quantity_cancelled',
        'row_total',
        'row_total_incl_tax',
        'weight',
        'volume',
        'fulfillment_status',
        'notes',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'row_total' => 'decimal:2',
        'row_total_incl_tax' => 'decimal:2',
        'weight' => 'decimal:2',
        'volume' => 'decimal:2',
        'fulfillment_status' => 'string',
      
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function scopeFulfilled($query)
    {
        return $query->where('fulfillment_status', 'fulfilled');
    }
    protected static function booted()
    {
        static::creating(function ($orderItem) {
            $orderItem->created_by = Auth::id() ?? null;
            $orderItem->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($orderItem) {
            $orderItem->updated_by = Auth::id() ?? null;
        });
    }
}
