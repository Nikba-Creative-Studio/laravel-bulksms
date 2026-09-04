# Laravel BulkSMS

A modern Laravel package for sending and managing SMS messages through the
[BulkSMS JSON REST API](https://www.bulksms.com/developer/json/v1/).

- Built on Laravel's HTTP client — fully testable with `Http::fake()`.
- Fluent message builder with Unicode, scheduling, routing groups and batches.
- Typed, immutable data objects and enums.
- Proper error handling via dedicated exceptions.

## Documentation

Full documentation lives in the [`docs/`](docs/README.md) directory:

- [Installation](docs/installation.md)
- [Configuration](docs/configuration.md)
- [Sending messages](docs/sending-messages.md)
- [Reading messages & profile](docs/reading-messages.md)
- [Data objects & enums](docs/data-objects.md)
- [Error handling](docs/error-handling.md)
- [Testing](docs/testing.md)
- [Upgrading from 1.x](docs/upgrading.md)
- [API reference](docs/api-reference.md)

## Requirements

- PHP 8.2+ (PHP 8.3+ for Laravel 13)
- Laravel 10, 11, 12 or 13

## Installation

```bash
composer require nikba/laravel-bulksms
```

Publish the config file:

```bash
php artisan vendor:publish --tag=bulksms-config
```

## Configuration

Create an API token in your BulkSMS account under
**Settings → Developer Settings → API Tokens**, then add it to your `.env`:

```dotenv
BULKSMS_TOKEN_ID=your_token_id
BULKSMS_TOKEN_SECRET=your_token_secret

# Optional
BULKSMS_FROM=MyBrand
BULKSMS_TIMEOUT=30
BULKSMS_RETRY_TIMES=0
```

> The package Base64-encodes the token id/secret pair for you. Username/password
> authentication is no longer supported by BulkSMS for accounts created after
> 2026-04-29.

## Usage

### Send a quick message

```php
use Nikba\BulkSms\Facades\BulkSms;

BulkSms::sendMessage('+447700900000', 'Hello World!');
```

### Fluent builder

```php
use Nikba\BulkSms\Enums\Encoding;
use Nikba\BulkSms\Enums\RoutingGroup;
use Nikba\BulkSms\Facades\BulkSms;

$messages = BulkSms::message()
    ->to('+447700900000')
    ->from('MyBrand')
    ->body('Dobrá práce! Jak se máš?')
    ->encoding(Encoding::UNICODE)      // or ->unicode()
    ->routingGroup(RoutingGroup::STANDARD)
    ->send();

foreach ($messages as $message) {
    echo $message->id.' → '.$message->statusType;
}
```

### Send to many recipients (batch)

```php
BulkSms::message()
    ->to(['+447700900000', '+447700900001'])
    ->body('Batch message')
    ->send();
```

### Collect replies (repliable)

```php
BulkSms::message()
    ->to('+447700900000')
    ->repliable()
    ->body('Reply YES to confirm')
    ->send();
```

### Schedule a message

```php
BulkSms::message()
    ->to('+447700900000')
    ->body('Reminder!')
    ->scheduleAt(now()->addDay(), 'Daily reminder')
    ->send();
```

### Read messages and profile

```php
$messages = BulkSms::messages(['limit' => 20]);
$message  = BulkSms::getMessage('12345');
$replies  = BulkSms::relatedReceivedMessages('12345');

$profile  = BulkSms::profile();
$balance  = BulkSms::credits();
```

## Error handling

Failed API calls throw `Nikba\BulkSms\Exceptions\BulkSmsRequestException`, which
exposes the HTTP status and the decoded API error payload:

```php
use Nikba\BulkSms\Exceptions\BulkSmsRequestException;

try {
    BulkSms::sendMessage('+447700900000', 'Hello');
} catch (BulkSmsRequestException $e) {
    report($e->getMessage()); // e.g. "Insufficient credits"
    $status = $e->status;     // e.g. 403
    $details = $e->errors;    // decoded API payload
}
```

## Testing

The package uses Laravel's HTTP client, so you can fake it in your own tests:

```php
use Illuminate\Support\Facades\Http;

Http::fake([
    'api.bulksms.com/*' => Http::response([['id' => '1']], 201),
]);
```

Run the package test suite:

```bash
composer test
composer analyse
composer format
```

## License

MIT © [Bargan Nicolai](mailto:office@nikba.com)
