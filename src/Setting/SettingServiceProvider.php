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
        include __DIR__ . '/../routes.php';

        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-settings');

        $this->setupRoutes($this->app->router);
    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Unisharp\Setting\Controllers'], function($router)
        {
            require __DIR__.'/../routes.php';
        });
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
