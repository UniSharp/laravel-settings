<?php namespace Unisharp\Setting;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;


/**
 * Class SettingServiceProvider
 * @package Unisharp\Setting
 */
class SettingServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';

        $this->loadTranslationsFrom(__DIR__.'/lang', 'laravel-settings');

        $this->loadViewsFrom(__DIR__.'/views', 'laravel-settings');

        $this->publishes([
            __DIR__ . '/config/setting.php' => config_path('setting.php', 'config'),
        ], 'setting_config');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/laravel-settings'),
        ], 'setting_views');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['laravel-settings'] = $this->app->share(function ()
        {
            return true;
        });
    }

}
