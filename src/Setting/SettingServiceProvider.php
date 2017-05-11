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
        $filename = '2015_08_06_184708_create_settings_table.php';

        $this->publishes([
            __DIR__ . '/../migrations/' . $filename => base_path('/database/migrations/' . $filename),
        ], 'settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Setting', '\Unisharp\Setting\Setting');

        $this->app->bind('\Unisharp\Setting\SettingStorageContract', '\Unisharp\Setting\EloquentStorage');
    }
}
