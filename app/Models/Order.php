<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_number',
        'invoice_number',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'total',
        'coupon_id',
        'coupon_code',
        'total_paid',
        'total_refunded',
        'currency',
        'payment_status',
        'payment_method',
        'transaction_id',
        'payment_notes',
        'shipping_address_id',
        'shipping_method',
        'shipping_weight',
        'tracking_number',
        'tracking_url',
        'user_id',
        'guest_session_id',
        'customer_email',
        'customer_phone',
        'customer_ip',
        'confirmed_at',
        'paid_at',
        'processing_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'notes',
        'admin_notes',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'status' => 'string',
        'payment_status' => 'string',
        'invoice_number' => 'integer',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_refunded' => 'decimal:2',
        'shipping_weight' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'paid_at' => 'datetime',
        'processing_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    protected static function booted()
    {
        static::creating(function ($order) {
            $order->created_by = Auth::id() ?? null;
            $order->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($order) {
            $order->updated_by = Auth::id() ?? null;
        });
    }

    public function getFormattedInvoiceNumberAttribute()
    {
        return $this->invoice_number ? 'INV-' . str_pad($this->invoice_number, 5, '0', STR_PAD_LEFT) : null;
    }
}
