<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractPersistedQueryFixtureWebserverRequestTestCase;

class POSTMethodParamsViaGETPersistedQueryFixtureWebserverRequestTest extends AbstractPersistedQueryFixtureWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-persisted-queries-post-method-params-via-get';
    }

    protected static function getEndpoint(string $dataName): string
    {
        return match ($dataName) {
            'by-post-not-passing-params'
                => 'graphql-query/persisted-query-executed-via-post-passing-params-via-get/',
            'by-post-passing-params-via-get'
                => 'graphql-query/persisted-query-executed-via-post-passing-params-via-get/?slug=non-existing',
            default => parent::getEndpoint($dataName),
        };
    }

    protected static function getEntryMethod(string $dataName): string
    {
        return match ($dataName) {
            'by-post-not-passing-params',
            'by-post-passing-params-via-get'
                => 'POST',
            default
                => 'GET',
        };
    }
}
