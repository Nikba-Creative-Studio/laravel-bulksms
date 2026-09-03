# Data objects & enums

[← Back to index](README.md)

## Data objects

The package returns immutable, typed data objects instead of raw arrays. Both
implement `Arrayable` and `JsonSerializable`, and expose the full decoded API
payload via a `raw` property.

### Message

`Nikba\BulkSms\Data\Message`

| Property         | Type            | Description |
|------------------|-----------------|-------------|
| `id`             | `?string`       | Message id. |
| `type`           | `?string`       | `SENT`, `RECEIVED`, etc. |
| `to`             | `?string`       | Recipient address (when scalar). |
| `from`           | `mixed`         | Sender (string or structured object). |
| `body`           | `?string`       | Message text. |
| `encoding`       | `?string`       | `TEXT`, `UNICODE` or `BINARY`. |
| `numberOfParts`  | `?int`          | Number of SMS parts. |
| `creditCost`     | `?float`        | Credits charged. |
| `statusType`     | `?string`       | Status type (e.g. `ACCEPTED`). |
| `userSuppliedId` | `?string`       | Your correlation id. |
| `submissionId`   | `?string`       | The submission this message belongs to. |
| `raw`            | `array`         | The full decoded payload. |

```php
$message->id;
$message->toArray();   // full payload
json_encode($message); // JSON of the full payload
```

### Profile

`Nikba\BulkSms\Data\Profile`

| Property         | Type      | Description |
|------------------|-----------|-------------|
| `id`             | `?string` | Account id. |
| `username`       | `?string` | Account username. |
| `creditBalance`  | `?float`  | Remaining credits. |
| `quotaSize`      | `?int`    | Daily send limit. |
| `quotaRemaining` | `?int`    | Messages you can still send today. |
| `raw`            | `array`   | The full decoded payload. |

## Enums

### Encoding

`Nikba\BulkSms\Enums\Encoding`

| Case      | Value       | When to use |
|-----------|-------------|-------------|
| `TEXT`    | `TEXT`      | Standard GSM 03.38 text (default). |
| `UNICODE` | `UNICODE`   | Characters outside the GSM 03.38 set. |
| `BINARY`  | `BINARY`    | Raw bytes, expressed as hex in the body. |

### RoutingGroup

`Nikba\BulkSms\Enums\RoutingGroup`

| Case       | Value      |
|------------|------------|
| `STANDARD` | `STANDARD` |
| `ECONOMY`  | `ECONOMY`  |
| `PREMIUM`  | `PREMIUM`  |

### SenderType

`Nikba\BulkSms\Enums\SenderType`

| Case            | Value           | Notes |
|-----------------|-----------------|-------|
| `INTERNATIONAL` | `INTERNATIONAL` | Numeric international address. |
| `ALPHANUMERIC`  | `ALPHANUMERIC`  | Up to 11 characters; not repliable. |
| `SHORTCODE`     | `SHORTCODE`     | Up to 6 digits. |
| `REPLIABLE`     | `REPLIABLE`     | BulkSMS collects replies for you. |
