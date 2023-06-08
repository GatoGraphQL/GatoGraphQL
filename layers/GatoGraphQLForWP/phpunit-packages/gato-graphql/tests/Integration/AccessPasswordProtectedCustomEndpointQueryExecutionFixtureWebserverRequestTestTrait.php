<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Password-Protected Custom Endpoint
 */
trait AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-custom-endpoints';
    }

    protected static function getEndpoint(): string
    {
        return sprintf(
            'graphql/password-protected-custom-endpoint/%s',
            static::accessClient()
                ? '?view=graphiql'
                : ''
        );
    }

    abstract protected static function accessClient(): bool;
}
