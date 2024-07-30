<?php

namespace Nikba\BulkSms;

use Illuminate\Support\ServiceProvider;

class BulkSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('bulksms', function ($app) {
            return new Services\BulkSmsService();
        });
    }

    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/bulksms.php' => config_path('bulksms.php'),
        ]);
    }
}
