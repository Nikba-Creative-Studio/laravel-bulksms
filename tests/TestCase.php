<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Tests;

use Nikba\BulkSms\BulkSmsServiceProvider;
use Nikba\BulkSms\Facades\BulkSms;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BulkSmsServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['BulkSms' => BulkSms::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('bulksms.token_id', 'test-token-id');
        $app['config']->set('bulksms.token_secret', 'test-token-secret');
    }
}
