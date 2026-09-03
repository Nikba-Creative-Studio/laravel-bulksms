# Sending messages

[← Back to index](README.md)

All examples use the facade. You can also inject
`Nikba\BulkSms\Services\BulkSmsService` and call the same methods.

```php
use Nikba\BulkSms\Facades\BulkSms;
```

## Simple message

The backwards-compatible shortcut. Returns an array of the sent message(s).

```php
BulkSms::sendMessage('+447700900000', 'Hello World!');
```

## The fluent builder

`BulkSms::message()` returns a [`MessageBuilder`](api-reference.md#messagebuilder).
Chain the options you need and call `send()`. It returns a
`Collection<int, Message>`.

```php
$messages = BulkSms::message()
    ->to('+447700900000')
    ->from('MyBrand')
    ->body('Hello World!')
    ->send();

foreach ($messages as $message) {
    echo $message->id.' → '.$message->statusType.PHP_EOL;
}
```

## Multiple recipients (batch)

Pass an array or any iterable of numbers. You can post up to 50,000 messages in
a batch (a few thousand per request is recommended).

```php
BulkSms::message()
    ->to(['+447700900000', '+447700900001'])
    ->body('Batch message')
    ->send();
```

## Sender id (`from`)

```php
// Alphanumeric or international, as a plain string
BulkSms::message()->from('MyBrand')->to('+447700900000')->body('Hi')->send();
```

For explicit sender types use `fromSender()` with the
[`SenderType`](data-objects.md#sendertype) enum:

```php
use Nikba\BulkSms\Enums\SenderType;

BulkSms::message()
    ->fromSender(SenderType::INTERNATIONAL, '+447911123456')
    ->to('+447700900000')
    ->body('Hi')
    ->send();
```

### Repliable messages

Ask BulkSMS to collect the recipient's reply on your behalf:

```php
BulkSms::message()
    ->to('+447700900000')
    ->repliable()
    ->body('Reply YES to confirm')
    ->send();
```

Retrieve those replies later with
[`relatedReceivedMessages()`](reading-messages.md#related-received-messages).

## Unicode & encoding

Use Unicode when the body contains characters outside the GSM 03.38 set.

```php
use Nikba\BulkSms\Enums\Encoding;

BulkSms::message()
    ->to('+447700900000')
    ->body('Dobrá práce! Jak se máš?')
    ->encoding(Encoding::UNICODE)   // or the ->unicode() shortcut
    ->send();
```

Alternatively, let the API auto-detect and upgrade non-GSM messages to Unicode:

```php
BulkSms::message()->to('+447700900000')->body('…')->autoUnicode()->send();
```

## Routing group

```php
use Nikba\BulkSms\Enums\RoutingGroup;

BulkSms::message()
    ->to('+447700900000')
    ->body('Hi')
    ->routingGroup(RoutingGroup::PREMIUM)
    ->send();
```

## Scheduling

Send a message in the future (up to two years). Credits are deducted
immediately; scheduled messages cannot be changed or cancelled once submitted.

```php
BulkSms::message()
    ->to('+447700900000')
    ->body('Reminder!')
    ->scheduleAt(now()->addDay(), 'Daily reminder')
    ->send();
```

`scheduleAt()` accepts a `DateTimeInterface` (formatted as an ISO-8601 string
with offset) or a raw string.

## Long messages

Set the maximum number of parts for a concatenated message (default is 3):

```php
BulkSms::message()->to('+447700900000')->body($long)->longMessageMaxParts(5)->send();
```

## Deduplication

If a network failure leaves you unsure whether a batch was accepted, retry with
the same `deduplication-id` — the API will not send it twice (the id expires
after ~12 hours).

```php
BulkSms::message()
    ->to('+447700900000')
    ->body('Once only')
    ->deduplicationId(20240601)
    ->send();
```

## Correlating with your data

Attach your own id (max 20 characters) to correlate messages with your records:

```php
BulkSms::message()->to('+447700900000')->body('Hi')->userSuppliedId('order-123')->send();
```

## Sending a raw payload

If you prefer to build the request body yourself (matching the API's
`SubmissionEntry` schema), pass it to `send()` directly:

```php
BulkSms::send([
    'to'   => '+447700900000',
    'body' => 'Hello',
    'from' => 'MyBrand',
], ['auto-unicode' => 'true']);
```
