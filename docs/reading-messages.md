# Reading messages & profile

[← Back to index](README.md)

```php
use Nikba\BulkSms\Facades\BulkSms;
```

## List messages

Returns a `Collection<int, Message>`. Pass any of the API's query filters.

```php
$messages = BulkSms::messages([
    'limit'     => 20,
    'sortOrder' => 'DESCENDING',
]);
```

Common filters:

| Filter      | Description |
|-------------|-------------|
| `filter`    | A filter expression (see the BulkSMS API docs). |
| `sortOrder` | `ASCENDING` or `DESCENDING`. |
| `limit`     | Maximum number of results. |
| `minId`     | Return messages with an id greater than this. |
| `maxId`     | Return messages with an id less than this. |

## Get a single message

```php
$message = BulkSms::getMessage('12345');

echo $message->body;
echo $message->statusType;
```

Returns a single [`Message`](data-objects.md#message) object.

## Related received messages

For a repliable sent message, fetch the mobile-originating (MO) replies
associated with it:

```php
$replies = BulkSms::relatedReceivedMessages('12345');
```

Returns a `Collection<int, Message>`.

## Account profile

```php
$profile = BulkSms::profile();

echo $profile->username;
echo $profile->creditBalance;
echo $profile->quotaRemaining;
```

Returns a [`Profile`](data-objects.md#profile) object.

## Credit balance

A convenience shortcut for `profile()->creditBalance`:

```php
$balance = BulkSms::credits(); // float|null
```
