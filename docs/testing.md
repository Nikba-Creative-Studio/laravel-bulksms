# Testing

[← Back to index](README.md)

## Faking the API in your app

The package uses Laravel's HTTP client under the hood, so you can fake all
BulkSMS traffic in your own tests with `Http::fake()` — no real requests are
made.

```php
use Illuminate\Support\Facades\Http;
use Nikba\BulkSms\Facades\BulkSms;

Http::fake([
    'api.bulksms.com/*' => Http::response([['id' => '1']], 201),
]);

BulkSms::sendMessage('+447700900000', 'Hello');

Http::assertSent(function ($request) {
    return $request->url() === 'https://api.bulksms.com/v1/messages'
        && $request['to'] === '+447700900000'
        && $request['body'] === 'Hello';
});
```

### Faking a failure

```php
Http::fake([
    'api.bulksms.com/*' => Http::response(['title' => 'Insufficient credits'], 403),
]);

// This will throw a BulkSmsRequestException
```

## Running the package test suite

```bash
composer test      # PHPUnit
composer analyse   # PHPStan (level 6)
composer format    # Laravel Pint
```

The suite runs against a matrix of PHP 8.2–8.4 and Laravel 10–13 in CI
(`.github/workflows/tests.yml`).
