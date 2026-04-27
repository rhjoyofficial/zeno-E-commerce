<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role?->slug === 'customer' && $user->status === 'active') {
            return $next($request);
        }

        if ($user && $user->status !== 'active') {
            abort(403, 'Your account has been suspended.');
        }

        abort(403, 'Unauthorized access. Customer privileges required.');
    }
}