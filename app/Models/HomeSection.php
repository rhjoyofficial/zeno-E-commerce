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
        return $this->hasMany(HomeSectionItem::class)->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Helper to get products for the section
    public function getProducts()
    {
        $products = collect();

        if ($this->type === 'new_arrivals') {
            $products = Product::with(['primaryImage', 'category', 'tags'])
                ->active()
                ->latest()
                ->take(8)
                ->get();
        } elseif ($this->type === 'fashion' && $this->category) {
            // Get all descendant category IDs (you'll need to implement getDescendantIds in Category model)
            $categoryIds = $this->category->getDescendantIds();
            $products = Product::with(['primaryImage', 'category', 'tags'])
                ->whereIn('category_id', $categoryIds)
                ->active()
                ->take(8)
                ->get();
        }

        // Map products to the expected format
        return $products->map(function ($product) {
            return [
                'id'            => $product->id,
                'image'         => $product->image_path,
                'title'         => $product->title,
                'price'         => $product->price,
                'discountPrice' => $product->discount_price ?: null,
                'badge'         => $product->discount ? 'Sale' : 'New',
                'stock'         => $product->stock_quantity > 0,
                'categories'    => $product->category ? [$product->category->categoryName] : [],
                'tags'          => $product->tags->pluck('tag')->toArray(),
                'avg_rating'    => $product->avg_rating,
                'slug'          => $product->slug,
            ];
        });
    }
}
