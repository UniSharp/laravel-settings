<?php

namespace Unisharp\Setting;

use Illuminate\Support\ServiceProvider;

/**
 * Class SettingServiceProvider
 * @package Unisharp\Setting
 */
class SettingServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';

        $this->loadViewsFrom(__DIR__.'/views', 'laravel-settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('\Unisharp\Setting\SettingInterface', '\Unisharp\Setting\Setting');
    }
}
