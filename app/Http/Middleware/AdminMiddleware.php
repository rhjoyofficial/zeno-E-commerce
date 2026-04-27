<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role?->slug === 'admin' && $user->status === 'active') {
            return $next($request);
        }

        if ($user && $user->status !== 'active') {
            abort(403, 'Your account has been suspended.');
        }

        abort(403, 'Unauthorized access. Administrator privileges required.');
    }
}