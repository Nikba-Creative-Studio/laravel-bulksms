# Installation

[← Back to index](README.md)

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | 8.2+ (8.3+ for Laravel 13) |
| Laravel     | 10, 11, 12 or 13 |

## Install via Composer

```bash
composer require nikba/laravel-bulksms
```

The service provider and the `BulkSms` facade are registered automatically
through Laravel's package discovery — no manual registration required.

## Publish the config file

```bash
php artisan vendor:publish --tag=bulksms-config
```

This copies `config/bulksms.php` into your application's `config` directory.

## Next steps

- [Configure your API token](configuration.md)
- [Send your first message](sending-messages.md)
