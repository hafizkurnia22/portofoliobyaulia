<?php

namespace App\Providers;

use App\Models\TentangSaya;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

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
        Paginator::useBootstrapFive();

        try {
            View::share('tentangSaya', TentangSaya::first());
        } catch (\Throwable $exception) {
            View::share('tentangSaya', null);
        }
    }
}
