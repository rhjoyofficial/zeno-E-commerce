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
        'banner_image',
        'category_id',
        'status',
        'order',
        'created_by',
        'updated_by'
    ];

    public function items()
    {
        return $this->hasMany(HomeSectionItem::class)->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Helper to get products for the section
    public function getProducts()
    {
        if ($this->type === 'new_arrivals') {
            return Product::latest()->take(8)->get(); // Adjust limit as needed
        } elseif ($this->type === 'fashion' && $this->category) {
            // Get all descendant category IDs (assuming you have a method for hierarchy)
            $categoryIds = $this->category->getDescendantIds(); // Implement this in Category model
            return Product::whereIn('category_id', $categoryIds)->take(8)->get();
        }
        return collect();
    }
}
