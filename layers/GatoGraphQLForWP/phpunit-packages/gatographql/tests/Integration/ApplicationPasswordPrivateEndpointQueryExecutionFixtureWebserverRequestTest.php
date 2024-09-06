<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class ApplicationPasswordPrivateEndpointQueryExecutionFixtureWebserverRequestTest extends AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-application-password-private';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql/nested-mutations/';
    }
}
