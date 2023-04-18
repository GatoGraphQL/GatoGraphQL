<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test the InternalGraphQLServer. It works like this:
 *
 * In the JSON response there is entry "internalGraphQLServerResponse",
 * which contains the response to the same requested GraphQL query,
 * but executed via `GraphQLServer`, using the configuration
 * of the InternalGraphQLServer.
 *
 * Hence, the response under this entry will be different
 * that the original response, as the two are using different
 * configurations.
 *
 * @see layers/GraphQLAPIForWP/phpunit-plugins/graphql-api-for-wp-testing/src/Executers/InternalGraphQLServerTestExecuter.php
 */
class InternalGraphQLServerQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use InternalGraphQLServerWebserverRequestTestTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-introspection-and-config';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-internal-graphql-server';
    }

    /**
     * Enable the "InternalGraphQLServer" testing via
     * the endpoint
     */
    protected function getEndpoint(): string
    {
        return $this->getInternalGraphQLServerEndpoint(
            'graphql/',
            false
        );
    }
}
