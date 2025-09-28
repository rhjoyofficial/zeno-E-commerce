<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShippingAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'guest_session_id',
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'is_default',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'is_default' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    protected static function booted()
    {
        static::creating(function ($address) {
            $address->created_by = Auth::id() ?? null;
            $address->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($address) {
            $address->updated_by = Auth::id() ?? null;
        });
    }
}
