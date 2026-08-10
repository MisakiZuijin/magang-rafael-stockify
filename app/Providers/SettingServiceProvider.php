<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class, fn() => new SettingService());
    }

    public function boot(): void
    {
        // Share ke semua view
        View::composer('*', function ($view) {
            $settings = app(SettingService::class)->all();
            $view->with('settings', $settings);
        });
    }
}
