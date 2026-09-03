# Upgrading from 1.x to 2.x

[← Back to index](README.md)

Version 2.0 is a modernisation of the package. It keeps the most common entry
point working, so most integrations upgrade with little or no code change.

## Requirements changed

- **PHP 8.2+** is now required (was 7.4/8.0).
- Supported Laravel versions are **10, 11 and 12**.

## What still works

`BulkSms::sendMessage($to, $message)` is unchanged and still returns an array:

```php
BulkSms::sendMessage('+447700900000', 'Hello World!'); // ✅ works as before
```

The legacy `BULKSMS_API_KEY` (a single, already Base64-encoded `tokenId:secret`
string) is still honoured when the new token pair is not set.

## Recommended changes

### 1. Switch to the token id/secret pair

Previously the config exposed a single `api_key`. The recommended setup now uses
two values, which the package encodes for you:

```dotenv
# Before
BULKSMS_API_KEY=base64_of_tokenId_colon_secret

# After (recommended)
BULKSMS_TOKEN_ID=your_token_id
BULKSMS_TOKEN_SECRET=your_token_secret
```

> The old, undocumented behaviour required you to pre-encode the value yourself.
> If authentication ever silently failed for you before, this is why — the new
> token pair fixes it.

### 2. Republish the config

```bash
php artisan vendor:publish --tag=bulksms-config --force
```

Note the publish **tag** is now `bulksms-config`. The old command
(`--provider="Nikba\BulkSms\BulkSmsServiceProvider"`) still works but publishes
everything.

## New features to adopt

- Fluent [`MessageBuilder`](sending-messages.md) with sender ids, Unicode,
  scheduling, routing groups and batches.
- Typed [data objects and enums](data-objects.md).
- Proper [error handling](error-handling.md) via `BulkSmsRequestException`.
- Extra endpoints: [list messages, get a message, replies, profile,
  credits](reading-messages.md).

## Behavioural change: errors now throw

In 1.x a failed request could surface an unhandled Guzzle exception. In 2.x every
non-successful response throws a `BulkSmsRequestException` carrying the status
code and decoded error body. Wrap calls in a `try/catch` where appropriate — see
[Error handling](error-handling.md).
