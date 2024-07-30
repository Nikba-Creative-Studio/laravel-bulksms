# Laravel BulkSMS Package

This package allows you to send SMS messages using the BulkSMS API in your Laravel application.

## Installation

```bash
composer require nikba/laravel-bulksms
```

## Configuration
Publish the configuration file:
```bash
php artisan vendor:publish --provider="Nikba\BulkSms\BulkSmsServiceProvider"
```

Add your BulkSMS API key to your .env file:
```bash
BULKSMS_API_KEY=your_api_key_here
```
## Usage
Use the BulkSms facade to send messages:

```php
use BulkSms;

BulkSms::sendMessage('1234567890', 'Hello World');
```
