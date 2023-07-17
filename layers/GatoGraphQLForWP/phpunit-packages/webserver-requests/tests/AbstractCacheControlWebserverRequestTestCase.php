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
        $responseHeaderEntries = [];
        foreach (static::provideCacheControlEntries() as $key => $value) {
            // Append ',' at the end of every cache-control
            $value[count($value) - 1] .=  ',';
            $responseHeaderEntries[$key] = $value;
        }
        return $responseHeaderEntries;
    }

    /**
     * @return array<string,string[]>
     */
    abstract public static function provideCacheControlEntries(): array;
}
