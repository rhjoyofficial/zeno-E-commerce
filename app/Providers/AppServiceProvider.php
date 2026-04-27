<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('register', fn(Request $r) =>
            Limit::perMinute(5)->by($r->ip())
        );

        RateLimiter::for('password-reset', fn(Request $r) =>
            Limit::perMinutes(10, 5)->by($r->input('email', '') . '|' . $r->ip())
        );

        RateLimiter::for('otp-verify', fn(Request $r) =>
            Limit::perMinutes(5, 10)->by($r->ip())
        );

        RateLimiter::for('otp-resend', fn(Request $r) =>
            Limit::perMinutes(10, 3)->by($r->ip())
        );

        RateLimiter::for('checkout', fn(Request $r) =>
            Limit::perMinute(10)->by($r->user()?->id ?: $r->ip())
        );

        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        Gate::define('isAdmin', function ($user) {
            return $user->role?->slug === 'admin';
        });
        Gate::define('isCustomer', function ($user) {
            return $user->role?->slug === 'customer';
        });
        Gate::define('isAdminOrCustomer', function ($user) {
            return in_array($user->role?->slug, ['admin', 'customer']);
        });
    }
}
