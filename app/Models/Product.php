<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'short_description',
        'price',
        'discount',
        'discount_price',
        'is_new_arrival',
        'has_variants',
        'stock_alert',
        'stock_quantity',
        'slug',
        'sku',
        'status',
        'category_id',
        'brand_id',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'status' => 'string',
        'discount' => 'boolean',
        'is_new_arrival' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'stock_alert' => 'integer',
        'has_variants' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }
    public function details()
    {
        return $this->hasOne(ProductDetail::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }
    public function wishes()
    {
        return $this->hasMany(ProductWish::class);
    }
    public function carts()
    {
        return $this->hasMany(ProductCart::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('status', 'active');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function sliders()
    {
        return $this->hasMany(ProductSlider::class);
    }
    public function scopeNewArrivals($query)
    {
        return $query->where('is_new_arrival', true);
    }
    public function scopeById($query, $productId)
    {
        return $query->where('id', $productId);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
    public function getShortDesAttribute()
    {
        return $this->short_description;
    }
    public function getFinalPriceAttribute()
    {
        return $this->discount && $this->discount_price
            ? $this->discount_price
            : $this->price;
    }

    public function getImagePathAttribute()
    {
        return $this->primaryImage
            ? $this->primaryImage->image_path
            : 'images/products/default.jpg';
    }
    public function availableSizes()
    {
        return $this->hasManyThrough(
            ProductSize::class,
            ProductVariant::class,
            'product_id',
            'id',
            'id',
            'size_id'
        )
            ->where('product_variants.status', 'active')
            ->select('product_sizes.*')
            ->distinct();
    }

    public function availableColors()
    {
        return $this->hasManyThrough(
            Color::class,
            ProductVariant::class,
            'product_id',
            'id',
            'id',
            'color_id'
        )
            ->where('product_variants.status', 'active')
            ->select('colors.*')
            ->distinct();
    }


    protected static function booted()
    {
        static::creating(function ($product) {
            $product->created_by = Auth::id() ?? null;
            $product->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($product) {
            $product->updated_by = Auth::id() ?? null;
        });
    }
}
