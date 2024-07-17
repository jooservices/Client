<?php

namespace JOOservices\XClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JOOservices\XClient\Services\XClient
 */
class XClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JOOservices\XClient\Services\XClient::class;
    }
}
