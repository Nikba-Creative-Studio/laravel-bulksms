# Configuration

[← Back to index](README.md)

## Get an API token

BulkSMS authenticates with an **API token**. Create one in your account under
**Settings → Developer Settings → API Tokens**. You will get:

- a **token ID** (used as the username)
- a **token secret** (used as the password)

The package Base64-encodes them into the `Authorization: Basic ...` header for
you.

> **Note:** Username/password authentication is no longer supported by BulkSMS
> for accounts created after 2026-04-29. Use an API token.

## Environment variables

Add the credentials to your `.env` file:

```dotenv
BULKSMS_TOKEN_ID=your_token_id
BULKSMS_TOKEN_SECRET=your_token_secret

# Optional
BULKSMS_FROM=MyBrand
BULKSMS_BASE_URL=https://api.bulksms.com/v1
BULKSMS_TIMEOUT=30
BULKSMS_RETRY_TIMES=0
BULKSMS_RETRY_SLEEP=200
```

## Config reference

The published `config/bulksms.php` exposes the following options:

| Key            | Env var                | Default                         | Description |
|----------------|------------------------|---------------------------------|-------------|
| `token_id`     | `BULKSMS_TOKEN_ID`     | `null`                          | API token ID (username). |
| `token_secret` | `BULKSMS_TOKEN_SECRET` | `null`                          | API token secret (password). |
| `api_key`      | `BULKSMS_API_KEY`      | `null`                          | **Legacy** pre-encoded `tokenId:secret` string. Used only when the pair above is not set. |
| `base_url`     | `BULKSMS_BASE_URL`     | `https://api.bulksms.com/v1`    | API base URL. |
| `from`         | `BULKSMS_FROM`         | `null`                          | Default sender id applied when a message has no explicit `from`. |
| `timeout`      | `BULKSMS_TIMEOUT`      | `30`                            | Request timeout in seconds. |
| `retry.times`  | `BULKSMS_RETRY_TIMES`  | `0`                             | Number of retries on transient failures. `0` disables retrying. |
| `retry.sleep`  | `BULKSMS_RETRY_SLEEP`  | `200`                           | Delay between retries in milliseconds. |

## Legacy `api_key`

If you are upgrading from 1.x and stored a single, already Base64-encoded
`tokenId:secret` value in `BULKSMS_API_KEY`, it still works — the package falls
back to it when `token_id`/`token_secret` are not configured. Prefer the token
pair for new integrations. See [Upgrading](upgrading.md).
