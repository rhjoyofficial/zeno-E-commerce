<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'brand_name',
        'brand_image',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'status' => 'string',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    protected static function booted()
    {
        static::creating(function ($brand) {
            $brand->created_by = Auth::id() ?? null;
            $brand->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($brand) {
            $brand->updated_by = Auth::id() ?? null;
        });
    }
}
