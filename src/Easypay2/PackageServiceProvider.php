<?php

namespace CuongHuynh\EasyPay2;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../../config/easypay2.php';
        $this->mergeConfigFrom($configPath, 'easypay2');

        $this->app['easypay2'] = $this->app->share(function($app) {
            $easypay2Config = $this->app['config']->get('easypay2');

            return new Easypay2($easypay2Config);
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../../config/easypay2.php';
        $this->publishes([$configPath => config_path('easypay2.php')], 'config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
