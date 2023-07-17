<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

abstract class AbstractCacheControlWebserverRequestTestCase extends AbstractResponseHeaderWebserverRequestTestCase
{
    protected function getHeaderName(): string
    {
        return 'Cache-Control';
    }

    /**
     * @return array<string,string[]>
     */
    final public static function provideResponseHeaderEntries(): array
    {
        return static::provideCacheControlEntries();
    }

    /**
     * @return array<string,string[]>
     */
    abstract public static function provideCacheControlEntries(): array;
}
