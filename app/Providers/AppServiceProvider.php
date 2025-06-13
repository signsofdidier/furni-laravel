<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        /* BEPAAL DE SUPER ADMIN */
        if (app()->isLocal()) {
            $admin = User::where('email', 'admin@gmail.com')->first();

            if ($admin && ! $admin->hasRole('super_admin')) {
                $admin->assignRole('super_admin');
            }
        }

        Paginator::useBootstrapFive();
    }
}
