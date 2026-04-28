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
        // Bind to the navbar partial — fires only when the navbar renders,
        // not on admin views or any view that bypasses the frontend layout.
        View::composer('frontend.navbar', function ($view) {
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