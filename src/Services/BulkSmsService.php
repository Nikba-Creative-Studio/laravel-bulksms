<?php

namespace Nikba\BulkSms\Services;

use Nikba\BulkSms\Http\Clients\BulkSmsClient;

class BulkSmsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new BulkSmsClient();
    }

    /**
     * Send an SMS message.
     *
     * @param string $to
     * @param string $message
     * @return array
     */
    public function sendMessage($to, $message)
    {
        return $this->client->sendMessage($to, $message);
    }
}
