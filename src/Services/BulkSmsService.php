<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Services;

use Illuminate\Support\Collection;
use Nikba\BulkSms\Data\Message;
use Nikba\BulkSms\Data\Profile;
use Nikba\BulkSms\Facades\BulkSms;
use Nikba\BulkSms\Http\Clients\BulkSmsClient;
use Nikba\BulkSms\Messages\MessageBuilder;

/**
 * The main entry point for the package, resolved from the container as
 * "bulksms" and exposed through the {@see BulkSms} facade.
 */
class BulkSmsService
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected readonly BulkSmsClient $client,
        protected readonly array $config = [],
    ) {}

    /**
     * Start composing a message with the fluent builder.
     */
    public function message(): MessageBuilder
    {
        $builder = new MessageBuilder($this);

        if (filled($this->config['from'] ?? null)) {
            $builder->from((string) $this->config['from']);
        }

        return $builder;
    }

    /**
     * Send a simple text message. Kept backwards compatible with v1.x.
     *
     * @param  string|iterable<string>  $to
     * @return array<string, mixed>|list<array<string, mixed>>
     */
    public function sendMessage(string|iterable $to, string $message): array
    {
        return $this->message()->to($to)->body($message)->send()
            ->map(static fn (Message $message): array => $message->toArray())
            ->all();
    }

    /**
     * Send a composed message (from a builder or a raw payload array).
     *
     * @param  MessageBuilder|array<string, mixed>|list<array<string, mixed>>  $submission
     * @param  array<string, mixed>  $query
     * @return Collection<int, Message>
     */
    public function send(MessageBuilder|array $submission, array $query = []): Collection
    {
        if ($submission instanceof MessageBuilder) {
            $query = $submission->toQuery() + $query;
            $submission = $submission->toPayload();
        }

        $response = $this->client->post('messages', $submission, $query);

        return $this->toMessages($response);
    }

    /**
     * Retrieve a list of messages.
     *
     * @param  array<string, mixed>  $filters  Query filters (filter, limit, sortOrder, ...).
     * @return Collection<int, Message>
     */
    public function messages(array $filters = []): Collection
    {
        return $this->toMessages($this->client->get('messages', $filters));
    }

    /**
     * Retrieve a single message by its id.
     */
    public function getMessage(string $id): Message
    {
        return Message::fromArray($this->client->get('messages/'.rawurlencode($id)));
    }

    /**
     * Retrieve the mobile-originating messages related to a sent message.
     *
     * @return Collection<int, Message>
     */
    public function relatedReceivedMessages(string $id): Collection
    {
        return $this->toMessages(
            $this->client->get('messages/'.rawurlencode($id).'/relatedReceivedMessages')
        );
    }

    /**
     * Retrieve the account profile.
     */
    public function profile(): Profile
    {
        return Profile::fromArray($this->client->get('profile'));
    }

    /**
     * Convenience accessor for the remaining credit balance.
     */
    public function credits(): ?float
    {
        return $this->profile()->creditBalance;
    }

    /**
     * @param  array<array-key, mixed>  $response
     * @return Collection<int, Message>
     */
    protected function toMessages(array $response): Collection
    {
        // A single-message response comes back as an associative array; a batch
        // (or a listing) comes back as a list. Normalise both to a collection.
        $items = array_is_list($response) ? $response : [$response];

        return Collection::make($items)
            ->filter(static fn ($item): bool => is_array($item))
            ->map(static fn (array $item): Message => Message::fromArray($item))
            ->values();
    }
}
