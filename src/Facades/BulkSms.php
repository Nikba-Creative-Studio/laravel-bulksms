<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Facades;

use Illuminate\Support\Facades\Facade;
use Nikba\BulkSms\Services\BulkSmsService;

/**
 * @method static \Nikba\BulkSms\Messages\MessageBuilder message()
 * @method static array<string, mixed>|list<array<string, mixed>> sendMessage(string|iterable<string> $to, string $message)
 * @method static \Illuminate\Support\Collection<int, \Nikba\BulkSms\Data\Message> send(\Nikba\BulkSms\Messages\MessageBuilder|array<string, mixed> $submission, array<string, mixed> $query = [])
 * @method static \Illuminate\Support\Collection<int, \Nikba\BulkSms\Data\Message> messages(array<string, mixed> $filters = [])
 * @method static \Nikba\BulkSms\Data\Message getMessage(string $id)
 * @method static \Illuminate\Support\Collection<int, \Nikba\BulkSms\Data\Message> relatedReceivedMessages(string $id)
 * @method static \Nikba\BulkSms\Data\Profile profile()
 * @method static float|null credits()
 *
 * @see BulkSmsService
 */
class BulkSms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bulksms';
    }
}
