<?php

namespace Nikba\BulkSms\Facades;

use Illuminate\Support\Facades\Facade;

class BulkSms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bulksms';
    }
}
