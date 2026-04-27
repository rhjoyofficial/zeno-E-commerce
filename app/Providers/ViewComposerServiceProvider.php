<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\NavigationMenu;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $menus = cache()->remember('navigation_menus', 3600, function () {
                return NavigationMenu::with(['items.children', 'megaMenuContents'])
                    ->where('status', 'active')
                    ->orderBy('position')
                    ->get();
            });
            
            $view->with('navigationMenus', $menus);
        });

        View::composer('*', function ($view) {
            $cartCount = app(\App\Services\CartService::class)->getCartCount();
            $view->with('cartCount', $cartCount);
        });
    }
}