<?php

namespace Unisharp\Setting;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

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
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Setting', function () {
            return new Setting;
        });

        $this->app->bind('\Unisharp\Setting\SettingStorageInterface', '\Unisharp\Setting\EloquentStorage');
    }
}
