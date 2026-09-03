# Changelog

All notable changes to `nikba/laravel-bulksms` will be documented in this file.

## [2.0.0] - 2026-09-03

### Added
- Fluent `MessageBuilder` (`BulkSms::message()->to()->body()->send()`) with support
  for sender ids, Unicode/binary encoding, routing groups, auto-unicode, scheduling,
  concatenation limits, user-supplied ids and deduplication.
- Immutable, typed data objects: `Data\Message` and `Data\Profile`.
- Enums: `Enums\Encoding`, `Enums\RoutingGroup`, `Enums\SenderType`.
- New endpoints: list messages (with filters), get a single message, related
  received messages, account profile and credit balance.
- Dedicated exceptions: `BulkSmsException` and `BulkSmsRequestException`
  (with API status code and decoded error payload).
- Config for a token id/secret pair, base URL, default sender, timeout and retry.
- Full test suite backed by `Http::fake()` (no live network calls), PHPStan,
  Laravel Pint and a GitHub Actions matrix (PHP 8.2–8.4 × Laravel 10–12).

### Changed
- **BREAKING:** minimum PHP is now 8.2; supported Laravel is 10, 11 and 12.
- Internal HTTP layer moved from raw Guzzle to Laravel's HTTP client.
- Authentication now correctly Base64-encodes the token id/secret pair for the
  Basic Auth header, and surfaces API errors as exceptions.

### Backwards compatibility
- `BulkSms::sendMessage($to, $message)` continues to work and returns an array.
- The legacy pre-encoded `BULKSMS_API_KEY` is still honoured when a token
  id/secret pair is not configured.
