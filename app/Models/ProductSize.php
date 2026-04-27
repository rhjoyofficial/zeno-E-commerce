<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductSize extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'size_id');
    }
    protected static function booted()
    {
        static::creating(function ($size) {
            $size->created_by = Auth::id() ?? null;
            $size->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($size) {
            $size->updated_by = Auth::id() ?? null;
        });
    }
}
