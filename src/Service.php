<?php

namespace MyVendor\MyPackage;

class Service extends \think\Service
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->bind('rabbitmq', Rabbitmq::class);

        $this->commands([
            //
        ]);
    }
}