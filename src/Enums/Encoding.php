<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Enums;

/**
 * Describes the content of a message body.
 *
 * @see https://www.bulksms.com/developer/json/v1/#tag/message
 */
enum Encoding: string
{
    /** Standard GSM 03.38 text. The default. */
    case TEXT = 'TEXT';

    /** Use when the body contains characters outside the GSM 03.38 set. */
    case UNICODE = 'UNICODE';

    /** A sequence of bytes, expressed as hexadecimal digits in the body. */
    case BINARY = 'BINARY';
}
