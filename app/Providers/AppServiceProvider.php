<?php

namespace App\Providers;

use App\Models\Store;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            static $store = false;

            if ($store === false) {
                try {
                    $store = Store::current();
                } catch (\Throwable $e) {
                    $store = null;
                }
            }

            $view->with('store', $store);
        });
    }
}
