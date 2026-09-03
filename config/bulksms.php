<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | API Token credentials
    |--------------------------------------------------------------------------
    |
    | The BulkSMS JSON REST API authenticates with an API token. Create one in
    | your account under Settings > Developer Settings > API Tokens, then supply
    | the token ID as the "id" and the token secret as the "secret". The package
    | Base64 encodes them for the Basic Auth header for you.
    |
    | Note: username/password authentication is no longer supported for accounts
    | created after 2026-04-29.
    |
    */

    'token_id' => env('BULKSMS_TOKEN_ID'),

    'token_secret' => env('BULKSMS_TOKEN_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Legacy pre-encoded key (backwards compatibility)
    |--------------------------------------------------------------------------
    |
    | Older versions of this package expected a single, already Base64-encoded
    | "tokenId:secret" string. It is still honoured when the token_id/secret pair
    | above is not set. Prefer the pair above for new integrations.
    |
    */

    'api_key' => env('BULKSMS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    */

    'base_url' => env('BULKSMS_BASE_URL', 'https://api.bulksms.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Default sender id
    |--------------------------------------------------------------------------
    |
    | Optional default "from" applied to every message when one is not supplied
    | explicitly. May be an international number (+447911123456) or an
    | alphanumeric sender id (max 11 chars). Leave null to use account defaults.
    |
    */

    'from' => env('BULKSMS_FROM'),

    /*
    |--------------------------------------------------------------------------
    | HTTP client behaviour
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('BULKSMS_TIMEOUT', 30),

    'retry' => [
        'times' => (int) env('BULKSMS_RETRY_TIMES', 0),
        'sleep' => (int) env('BULKSMS_RETRY_SLEEP', 200),
    ],

];
