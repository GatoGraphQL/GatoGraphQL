<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaMutationsFixtureEndpointWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-mutations';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }
}
