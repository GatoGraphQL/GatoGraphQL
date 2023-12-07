<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractPersistedQueryFixtureWebserverRequestTestCase;

class PassParamsViaGETOrNotPersistedQueryFixtureWebserverRequestTest extends AbstractPersistedQueryFixtureWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-persisted-queries-pass-params-via-get-or-not';
    }

    protected static function getEndpoint(string $dataName): string
    {
        return match ($dataName) {
            'by-post-not-passing-params',
            'by-get-not-passing-params'
                => 'graphql-query/persisted-query-executed-via-post-passing-params-via-get/',
            'by-post-passing-params-via-get',
            'by-get-passing-params-via-get'
                => 'graphql-query/persisted-query-executed-via-post-passing-params-via-get/?slug=hello-world',
            default => parent::getEndpoint($dataName),
        };
    }

    protected static function getEntryMethod(string $dataName): string
    {
        return match ($dataName) {
            'by-post-not-passing-params',
            'by-post-passing-params-via-get'
                => 'POST',
            'by-get-not-passing-params',
            'by-get-passing-params-via-get'
                => 'GET',
            default
                => 'GET',
        };
    }
}
