<?php

namespace App\Providers;

use App\Support\MiniDriveStorageUsage;
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
        View::composer('partials.sidebar', function ($view): void {
            $view->with('miniDriveStorage', app(MiniDriveStorageUsage::class)->summary());
        });
    }
}
