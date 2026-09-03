<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Enums;

/**
 * Selects the routing used to deliver a message.
 */
enum RoutingGroup: string
{
    case STANDARD = 'STANDARD';
    case ECONOMY = 'ECONOMY';
    case PREMIUM = 'PREMIUM';
}
