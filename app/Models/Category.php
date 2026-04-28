<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

    private static ?array $categoryParentMap = null;

    public function getDescendantIds(): array
    {
        $version = Cache::get('categories_version', 1);

        return Cache::remember("category_descendants_{$this->id}_v{$version}", 3600, function () {
            if (static::$categoryParentMap === null) {
                static::$categoryParentMap = static::pluck('parent_id', 'id')->all();
            }

            $ids = [];
            $queue = [$this->id];
            $maxIterations = count(static::$categoryParentMap) + 1;
            $iterations = 0;

            while (!empty($queue) && $iterations < $maxIterations) {
                $currentId = array_shift($queue);
                $ids[] = $currentId;
                foreach (static::$categoryParentMap as $childId => $parentId) {
                    if ($parentId === $currentId && !in_array($childId, $ids, true)) {
                        $queue[] = $childId;
                    }
                }
                $iterations++;
            }

            return $ids;
        });
    }

    protected static function booted(): void
    {
        static::creating(function ($category) {
            $category->created_by = Auth::id() ?? null;
            $category->updated_by = Auth::id() ?? null;
        });
        static::updating(function ($category) {
            $category->updated_by = Auth::id() ?? null;
        });
        static::saved(function () {
            static::$categoryParentMap = null;
            Cache::increment('categories_version');
        });
        static::deleted(function () {
            static::$categoryParentMap = null;
            Cache::increment('categories_version');
        });
    }
}
