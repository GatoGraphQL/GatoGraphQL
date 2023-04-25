<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Custom Endpoint
 */
trait AccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-custom-endpoints';
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            'graphql/private-custom-endpoint/%s',
            $this->accessClient()
                ? '?view=graphiql'
                : ''
        );
    }

    abstract protected function accessClient(): bool;
}
