<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\NavigationMenu;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bind only to the frontend layout — fires once per frontend page request,
        // not on every admin view, include, or partial.
        View::composer('layouts.app', function ($view) {
            $menus = cache()->remember('navigation_menus', 3600, function () {
                return NavigationMenu::with(['items.children', 'megaMenuContents'])
                    ->where('status', 'active')
                    ->orderBy('position')
                    ->get();
            });

            $view->with('navigationMenus', $menus);
            $view->with('cartCount', app(CartService::class)->getCartCount());
        });
    }
}