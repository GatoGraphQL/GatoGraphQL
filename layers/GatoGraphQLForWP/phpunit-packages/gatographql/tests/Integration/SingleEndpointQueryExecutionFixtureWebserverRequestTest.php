<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SingleEndpointQueryExecutionFixtureWebserverRequestTest extends AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-single-endpoint';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql/';
    }
}
