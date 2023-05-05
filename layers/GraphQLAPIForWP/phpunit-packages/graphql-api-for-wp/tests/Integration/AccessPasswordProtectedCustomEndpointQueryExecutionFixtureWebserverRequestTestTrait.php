<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Password-Protected Custom Endpoint
 */
trait AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-custom-endpoints';
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            'graphql/password-protected-custom-endpoint/%s',
            $this->accessClient()
                ? '?view=graphiql'
                : ''
        );
    }

    abstract protected function accessClient(): bool;
}
