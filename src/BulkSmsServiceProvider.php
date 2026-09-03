<?php

declare(strict_types=1);

namespace Nikba\BulkSms;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;
use Nikba\BulkSms\Http\Clients\BulkSmsClient;
use Nikba\BulkSms\Services\BulkSmsService;

class BulkSmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bulksms.php', 'bulksms');

        $this->app->singleton(BulkSmsClient::class, function (Application $app): BulkSmsClient {
            return new BulkSmsClient(
                $app->make(HttpFactory::class),
                $app['config']->get('bulksms', []),
            );
        });

        $this->app->singleton('bulksms', function (Application $app): BulkSmsService {
            return new BulkSmsService(
                $app->make(BulkSmsClient::class),
                $app['config']->get('bulksms', []),
            );
        });

        $this->app->alias('bulksms', BulkSmsService::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/bulksms.php' => $this->app->configPath('bulksms.php'),
            ], 'bulksms-config');
        }
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return ['bulksms', BulkSmsService::class, BulkSmsClient::class];
    }
}
