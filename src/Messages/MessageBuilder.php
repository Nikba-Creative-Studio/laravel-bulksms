<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Messages;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Nikba\BulkSms\Data\Message;
use Nikba\BulkSms\Enums\Encoding;
use Nikba\BulkSms\Enums\RoutingGroup;
use Nikba\BulkSms\Enums\SenderType;
use Nikba\BulkSms\Services\BulkSmsService;

/**
 * A fluent builder for composing and sending a single SMS submission.
 *
 * @example
 *  BulkSms::message()
 *      ->to('+447700900000')
 *      ->from('MyBrand')
 *      ->body('Hello World!')
 *      ->send();
 */
class MessageBuilder
{
    /** @var list<string> */
    protected array $to = [];

    protected mixed $from = null;

    protected ?string $body = null;

    protected ?Encoding $encoding = null;

    protected ?RoutingGroup $routingGroup = null;

    protected ?int $longMessageMaxParts = null;

    protected ?string $userSuppliedId = null;

    /** @var array<string, mixed> */
    protected array $query = [];

    public function __construct(protected readonly BulkSmsService $service) {}

    /**
     * Add one or more recipients (international format, e.g. +447700900000).
     *
     * @param  string|iterable<string>  $numbers
     */
    public function to(string|iterable $numbers): static
    {
        foreach (is_string($numbers) ? [$numbers] : $numbers as $number) {
            $this->to[] = (string) $number;
        }

        return $this;
    }

    /**
     * Set the sender id. Accepts a plain string, or a type + address pair for
     * international/alphanumeric/shortcode/repliable senders.
     */
    public function from(string $address): static
    {
        $this->from = $address;

        return $this;
    }

    public function fromSender(SenderType $type, ?string $address = null): static
    {
        $this->from = array_filter([
            'type' => $type->value,
            'address' => $address,
        ], static fn ($value) => $value !== null);

        return $this;
    }

    /**
     * Ask BulkSMS to collect replies to this message on your behalf.
     */
    public function repliable(): static
    {
        return $this->fromSender(SenderType::REPLIABLE);
    }

    public function body(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function encoding(Encoding $encoding): static
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function unicode(): static
    {
        return $this->encoding(Encoding::UNICODE);
    }

    public function routingGroup(RoutingGroup $routingGroup): static
    {
        $this->routingGroup = $routingGroup;

        return $this;
    }

    public function longMessageMaxParts(int $parts): static
    {
        $this->longMessageMaxParts = $parts;

        return $this;
    }

    public function userSuppliedId(string $id): static
    {
        $this->userSuppliedId = $id;

        return $this;
    }

    /**
     * Automatically upgrade non-GSM messages to Unicode (query parameter).
     */
    public function autoUnicode(bool $enabled = true): static
    {
        $this->query['auto-unicode'] = $enabled ? 'true' : 'false';

        return $this;
    }

    /**
     * Schedule the message to be sent at a future date.
     */
    public function scheduleAt(DateTimeInterface|string $when, ?string $description = null): static
    {
        $this->query['schedule-date'] = $when instanceof DateTimeInterface
            ? $when->format(DateTimeInterface::ATOM)
            : $when;

        if ($description !== null) {
            $this->query['schedule-description'] = $description;
        }

        return $this;
    }

    /**
     * Guard against sending the same batch twice on a network retry.
     */
    public function deduplicationId(int $id): static
    {
        $this->query['deduplication-id'] = $id;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        $payload = [
            'to' => count($this->to) === 1 ? $this->to[0] : $this->to,
            'body' => $this->body,
        ];

        if ($this->from !== null) {
            $payload['from'] = $this->from;
        }

        if ($this->encoding !== null) {
            $payload['encoding'] = $this->encoding->value;
        }

        if ($this->routingGroup !== null) {
            $payload['routingGroup'] = $this->routingGroup->value;
        }

        if ($this->longMessageMaxParts !== null) {
            $payload['longMessageMaxParts'] = $this->longMessageMaxParts;
        }

        if ($this->userSuppliedId !== null) {
            $payload['userSuppliedId'] = $this->userSuppliedId;
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function toQuery(): array
    {
        return $this->query;
    }

    /**
     * Send the composed message.
     *
     * @return Collection<int, Message>
     */
    public function send(): Collection
    {
        return $this->service->send($this);
    }
}
