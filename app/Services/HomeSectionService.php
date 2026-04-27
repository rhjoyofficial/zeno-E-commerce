<?php

namespace App\Services;

use App\Models\HomeSection;
use App\Models\Product;
use Illuminate\Support\Collection;

class HomeSectionService
{
    /**
     * Load all active sections with their items and category, eager-loaded.
     */
    public function getActiveSections(): Collection
    {
        return HomeSection::active()
            ->orderBy('order')
            ->with(['items', 'category'])
            ->get();
    }

    /**
     * Return the product collection for a given section.
     * All DB hits happen here — zero queries in the view.
     */
    public function getProductsForSection(HomeSection $section, int $limit = 8): Collection
    {
        if ($section->type === 'new_arrivals') {
            return Product::with(['variants', 'primaryImage', 'category'])
                ->active()
                ->latest()
                ->take($limit)
                ->get();
        }

        if ($section->type === 'fashion' && $section->category) {
            $categoryIds = $section->category->getDescendantIds();
            return Product::with(['variants', 'primaryImage', 'category'])
                ->whereIn('category_id', $categoryIds)
                ->active()
                ->take($limit)
                ->get();
        }

        return collect();
    }
}
