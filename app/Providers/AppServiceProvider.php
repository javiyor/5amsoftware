<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\Support\CompanySettingsService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CompanySettingsService::class);
    }

    public function boot(): void
    {
        $this->configureGates();
        $this->configureRateLimiters();
        $this->shareCompanySettings();
    }

    private function shareCompanySettings(): void
    {
        view()->composer('*', function (View $view): void {
            $view->with('company', app(CompanySettingsService::class)->all());
        });
    }

    private function configureGates(): void
    {
        Gate::define('access-admin', fn (User $user): bool => $user->hasAnyRole([
            UserRole::SuperAdmin,
            UserRole::Admin,
            UserRole::Operator,
        ]));

        Gate::define('access-customer', fn (User $user): bool => $user->isCustomer());

        Gate::define('manage-users', fn (User $user): bool => $user->hasAnyRole([
            UserRole::SuperAdmin,
            UserRole::Admin,
        ]));

        Gate::define('manage-billing', fn (User $user): bool => $user->hasAnyRole([
            UserRole::SuperAdmin,
            UserRole::Admin,
            UserRole::Operator,
        ]));
    }

    private function configureRateLimiters(): void
    {
        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
        });

        RateLimiter::for('demo', function ($request) {
            return Limit::perMinutes(60, 3)->by($request->ip());
        });

        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
