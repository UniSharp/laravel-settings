<?php

namespace Unisharp\Setting;

use Illuminate\Support\ServiceProvider;

/**
 * Class SettingServiceProvider.
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
            __DIR__.'/../../database/migrations/'.$filename => base_path('/database/migrations/'.$filename),
        ], 'settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Setting', Setting::class);
        $this->app->bind(SettingStorageContract::class, EloquentStorage::class);
    }
}
