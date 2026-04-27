<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'section_title',
        'section_subtitle',
        'banner_image',
        'category_id',
        'status',
        'order',
        'created_by',
        'updated_by'
    ];

    public function items()
    {
        return $this->hasMany(HomeSectionCategory::class)->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Scope for new arrivals
    public function scopeNewArrivals($query)
    {
        return $query->where('type', 'new_arrivals');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
