<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Exceptions;

use RuntimeException;

/**
 * Base exception for every error raised by the BulkSMS package.
 */
class BulkSmsException extends RuntimeException
{
    public static function missingCredentials(): self
    {
        return new self(
            'No BulkSMS credentials configured. Set BULKSMS_TOKEN_ID and '
            .'BULKSMS_TOKEN_SECRET (or the legacy BULKSMS_API_KEY) in your environment.'
        );
    }
}
