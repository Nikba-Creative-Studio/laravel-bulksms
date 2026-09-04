# Laravel BulkSMS — Documentation

A modern Laravel package for sending and managing SMS messages through the
[BulkSMS JSON REST API](https://www.bulksms.com/developer/json/v1/).

## Contents

1. [Installation](installation.md)
2. [Configuration](configuration.md)
3. [Sending messages](sending-messages.md)
4. [Reading messages & profile](reading-messages.md)
5. [Data objects & enums](data-objects.md)
6. [Error handling](error-handling.md)
7. [Testing](testing.md)
8. [Upgrading from 1.x](upgrading.md)
9. [API reference](api-reference.md)

## Quick start

```php
use Nikba\BulkSms\Facades\BulkSms;

// Simple
BulkSms::sendMessage('+447700900000', 'Hello World!');

// Fluent
BulkSms::message()
    ->to('+447700900000')
    ->from('MyBrand')
    ->body('Hello World!')
    ->send();
```

## Requirements

- PHP 8.2+ (PHP 8.3+ for Laravel 13)
- Laravel 10, 11, 12 or 13
