<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /**
         * Super Admin Access Override
         *
         * This allows users with the "admin" role to bypass all
         * permission and policy checks throughout the application.
         *
         * ⚠️ Caution: Use with care — any user with the "admin" role
         * will have unrestricted access to all actions and resources.
         */
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
