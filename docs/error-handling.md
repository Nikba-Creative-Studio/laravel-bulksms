# Error handling

[← Back to index](README.md)

## Exceptions

All package exceptions extend `Nikba\BulkSms\Exceptions\BulkSmsException`
(itself a `RuntimeException`).

| Exception                  | Thrown when |
|----------------------------|-------------|
| `BulkSmsException`         | Base class. Also thrown directly when no credentials are configured. |
| `BulkSmsRequestException`  | The API responds with a non-successful HTTP status (4xx/5xx). |

## Handling failed requests

`BulkSmsRequestException` exposes the HTTP status and the decoded API error
payload, so you can react to specific conditions (for example, insufficient
credits on a `403`):

```php
use Nikba\BulkSms\Exceptions\BulkSmsRequestException;
use Nikba\BulkSms\Facades\BulkSms;

try {
    BulkSms::sendMessage('+447700900000', 'Hello');
} catch (BulkSmsRequestException $e) {
    $e->getMessage(); // e.g. "Insufficient credits"
    $e->status;       // int, e.g. 403
    $e->errors;       // array — the decoded API error payload
}
```

## Missing credentials

If neither the `token_id`/`token_secret` pair nor the legacy `api_key` is
configured, a `BulkSmsException` is thrown as soon as a request is attempted:

```
No BulkSMS credentials configured. Set BULKSMS_TOKEN_ID and BULKSMS_TOKEN_SECRET
(or the legacy BULKSMS_API_KEY) in your environment.
```

See [Configuration](configuration.md).

## Retries

Transient failures can be retried automatically by setting `retry.times` in the
config (see [Configuration](configuration.md)). Retries apply to connection-level
failures and are handled by Laravel's HTTP client.
