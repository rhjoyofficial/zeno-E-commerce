<?php

namespace App\Services;

use App\Models\HomeSection;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeSectionService
{
    /**
     * Load all active sections with their items and category, eager-loaded.
     */
    public function getActiveSections(): Collection
    {
        return Cache::remember('home_sections_active', 3600, fn () =>
            HomeSection::active()
                ->orderBy('order')
                ->with(['items', 'category'])
                ->get()
        );
    }

    /**
     * Return the product collection for a given section.
     * All DB hits happen here — zero queries in the view.
     */
    public function getProductsForSection(HomeSection $section, int $limit = 8): Collection
    {
        return Cache::remember("section_products_{$section->id}", 3600, function () use ($section, $limit) {
            $with = ['activeVariants.color', 'activeVariants.size', 'primaryImage', 'category'];

            if ($section->type === 'new_arrivals') {
                return Product::with($with)
                    ->active()
                    ->latest()
                    ->take($limit)
                    ->get();
            }

            if ($section->type === 'fashion') {
                if (!$section->category) {
                    Log::warning("HomeSection [{$section->id}] \"{$section->title}\" is type 'fashion' but has no category_id — no products will be shown.");
                    return collect();
                }

                $categoryIds = $section->category->getDescendantIds();
                return Product::with($with)
                    ->whereIn('category_id', $categoryIds)
                    ->active()
                    ->take($limit)
                    ->get();
            }

            return collect();
        });
    }
}
