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

    /**
     * Get the profile.
     *
     * @return array
     */
    public function getProfile()
    {
        return $this->client->getProfile();
    }

    /**
     * Get messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->client->getMessages();
    }

    /**
     * Get a message.
     *
     * @param int $id
     * @return array
     */
    public function getMessage($id)
    {
        return $this->client->getMessage($id);
    }
}
