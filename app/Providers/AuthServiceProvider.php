<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;


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
        // Define a gate to check if the user is an admin
        Gate::define('access-admin', function ($user) {
            return $user->role === 'superadmin'; // assuming your user has a 'role' column
        });
    }
}
