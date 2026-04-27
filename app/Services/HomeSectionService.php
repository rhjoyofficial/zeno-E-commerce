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
    public function getProductsForSection(HomeSection $section): Collection
    {
        if ($section->type === 'new_arrivals') {
            return Product::with(['primaryImage', 'category'])
                ->active()
                ->latest()
                ->take(8)
                ->get();
        }

        if ($section->type === 'fashion' && $section->category) {
            $categoryIds = $section->category->getDescendantIds();
            return Product::with(['primaryImage', 'category'])
                ->whereIn('category_id', $categoryIds)
                ->active()
                ->take(8)
                ->get();
        }

        return collect();
    }
}
