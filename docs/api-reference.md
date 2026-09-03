# API reference

[← Back to index](README.md)

## BulkSmsService / `BulkSms` facade

`Nikba\BulkSms\Services\BulkSmsService` — resolved from the container as
`bulksms` and exposed through the `Nikba\BulkSms\Facades\BulkSms` facade.

| Method | Returns | Description |
|--------|---------|-------------|
| `message()` | `MessageBuilder` | Start composing a message with the fluent builder. |
| `sendMessage(string\|iterable $to, string $message)` | `array` | Send a simple text message (backwards compatible). |
| `send(MessageBuilder\|array $submission, array $query = [])` | `Collection<int, Message>` | Send a composed message or raw payload. |
| `messages(array $filters = [])` | `Collection<int, Message>` | List messages, optionally filtered. |
| `getMessage(string $id)` | `Message` | Retrieve one message by id. |
| `relatedReceivedMessages(string $id)` | `Collection<int, Message>` | Replies (MOs) related to a sent message. |
| `profile()` | `Profile` | Retrieve the account profile. |
| `credits()` | `?float` | Remaining credit balance. |

## MessageBuilder

`Nikba\BulkSms\Messages\MessageBuilder` — every method returns `$this` unless
noted, so calls chain.

| Method | Description |
|--------|-------------|
| `to(string\|iterable $numbers)` | Add one or more recipients. |
| `from(string $address)` | Set the sender as a plain string. |
| `fromSender(SenderType $type, ?string $address = null)` | Set the sender with an explicit type. |
| `repliable()` | Make the message repliable (BulkSMS collects replies). |
| `body(string $body)` | Set the message text. |
| `encoding(Encoding $encoding)` | Set the encoding. |
| `unicode()` | Shortcut for `encoding(Encoding::UNICODE)`. |
| `routingGroup(RoutingGroup $group)` | Set the routing group. |
| `longMessageMaxParts(int $parts)` | Max parts for a concatenated message. |
| `userSuppliedId(string $id)` | Attach your correlation id (max 20 chars). |
| `autoUnicode(bool $enabled = true)` | Auto-upgrade non-GSM messages to Unicode. |
| `scheduleAt(DateTimeInterface\|string $when, ?string $description = null)` | Schedule for the future. |
| `deduplicationId(int $id)` | Guard against duplicate submissions. |
| `toPayload()` | Get the request body array. |
| `toQuery()` | Get the query-string array. |
| `send()` | **Returns `Collection<int, Message>`.** Send the message. |

## Data objects

- `Nikba\BulkSms\Data\Message` — see [Data objects](data-objects.md#message).
- `Nikba\BulkSms\Data\Profile` — see [Data objects](data-objects.md#profile).

## Enums

- `Nikba\BulkSms\Enums\Encoding`
- `Nikba\BulkSms\Enums\RoutingGroup`
- `Nikba\BulkSms\Enums\SenderType`

See [Data objects & enums](data-objects.md#enums).

## Exceptions

- `Nikba\BulkSms\Exceptions\BulkSmsException`
- `Nikba\BulkSms\Exceptions\BulkSmsRequestException` — has `->status` (int) and
  `->errors` (array).

See [Error handling](error-handling.md).

## Underlying API

This package targets the BulkSMS JSON REST API v1. The full upstream spec is at
<https://www.bulksms.com/developer/json/v1/>.

| Package method | HTTP call |
|----------------|-----------|
| `send()` / `sendMessage()` | `POST /messages` |
| `messages()` | `GET /messages` |
| `getMessage()` | `GET /messages/{id}` |
| `relatedReceivedMessages()` | `GET /messages/{id}/relatedReceivedMessages` |
| `profile()` / `credits()` | `GET /profile` |
