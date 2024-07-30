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
                'Authorization' => 'Basic ' . config('bulksms.api_key'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getProfile()
    {
        $response = $this->client->get('profile', [
            'headers' => [
                'Authorization' => 'Basic ' . config('bulksms.api_key'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getMessages()
    {
        $response = $this->client->get('messages', [
            'headers' => [
                'Authorization' => 'Basic ' . config('bulksms.api_key'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getMessage($id)
    {
        $response = $this->client->get('messages/' . $id, [
            'headers' => [
                'Authorization' => 'Basic ' . config('bulksms.api_key'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
