<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\CartService;

class SyncCartOnLogin
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Session::has('cart')) {
            $this->cartService->syncCart();
        }

        return $next($request);
    }
}
