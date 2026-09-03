<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Enums;

/**
 * The type of a sender id supplied in the "from" field.
 */
enum SenderType: string
{
    case INTERNATIONAL = 'INTERNATIONAL';
    case ALPHANUMERIC = 'ALPHANUMERIC';
    case SHORTCODE = 'SHORTCODE';

    /** Ask BulkSMS to collect replies to the message on your behalf. */
    case REPLIABLE = 'REPLIABLE';
}
