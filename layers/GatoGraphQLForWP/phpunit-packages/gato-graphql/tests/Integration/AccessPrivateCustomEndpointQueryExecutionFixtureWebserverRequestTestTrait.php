<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Custom Endpoint
 */
trait AccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-custom-endpoints';
    }

    protected static function getEndpoint(): string
    {
        return sprintf(
            'graphql/private-custom-endpoint/%s',
            static::accessClient()
                ? '?view=graphiql'
                : ''
        );
    }

    abstract protected static function accessClient(): bool;
}
