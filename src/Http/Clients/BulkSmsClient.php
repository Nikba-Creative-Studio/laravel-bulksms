<?php

namespace Nikba\BulkSms\Http\Clients;

use GuzzleHttp\Client;

class BulkSmsClient
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.bulksms.com/v1/',
        ]);
    }

    public function sendMessage($to, $message)
    {
        $response = $this->client->post('messages', [
            'json' => [
                'to' => $to,
                'body' => $message,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . config('bulksms.api_key'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
