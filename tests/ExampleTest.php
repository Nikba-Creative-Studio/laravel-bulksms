<?php

namespace Nikba\BulkSms\Tests;

use Orchestra\Testbench\TestCase;
use Nikba\BulkSms\Facades\BulkSms;
use Nikba\BulkSms\BulkSmsServiceProvider;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BulkSmsServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'BulkSms' => BulkSms::class,
        ];
    }

    /** @test */
    public function it_can_send_an_sms_message()
    {
        $response = BulkSms::sendMessage('1234567890', 'Hello World');

        $this->assertArrayHasKey('success', $response);
    }
}
