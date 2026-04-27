<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_name',
        'category_image',
        'status',
        'parent_id',
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
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeHasParent($query)
    {
        return $query->whereNotNull('parent_id');
    }
    public function scopeHasNotParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getCategoryNameAttribute()
    {
        return $this->category_name;
    }

    public function getDescendantIds()
    {
        $ids = [$this->id];
        $children = $this->children;
        foreach ($children as $child) {
            $ids = array_merge($ids, $child->getDescendantIds());
        }
        return $ids;
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->created_by = Auth::id() ?? null;
            $category->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($category) {
            $category->updated_by = Auth::id() ?? null;
        });
    }
}
