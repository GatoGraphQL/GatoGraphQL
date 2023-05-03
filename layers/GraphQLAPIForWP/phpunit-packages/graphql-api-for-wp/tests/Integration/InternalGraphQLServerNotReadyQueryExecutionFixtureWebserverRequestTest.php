<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCaseCase;

/**
 * Test the InternalGraphQLServer throws the GraphQLServerNotReady exception.
 *
 * @see layers/GraphQLAPIForWP/phpunit-plugins/graphql-api-for-wp-testing/src/Executers/InternalGraphQLServerTestExecuter.php
 */
class InternalGraphQLServerNotReadyQueryExecutionFixtureWebserverRequestTest extends AbstractEndpointWebserverRequestTestCaseCase
{
    use InternalGraphQLServerWebserverRequestTestTrait;

    protected function getEndpoint(): string
    {
        return $this->getInternalGraphQLServerEndpoint(
            'graphql/',
            false,
            true
        );
    }

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $query = $this->getGraphQLQuery();
        $endpoint = $this->getEndpoint();
        $expectedResponseBody = $this->getGraphQLExpectedResponse();
        $entries = [];
        $entries['graphql-server-not-ready'] = [
            'application/json',
            $expectedResponseBody,
            $endpoint,
            [],
            $query,
        ];
        return $entries;
    }

    protected function getGraphQLQuery(): string
    {
        return <<<GRAPHQL
            {
                id
            }
        GRAPHQL;
    }

    protected function getGraphQLExpectedResponse(): string
    {
        return <<<JSON
        {
            "code": "internal_server_error",
            "message": "Uncaught GraphQLAPI\\\\GraphQLAPI\\\\Exception\\\\GraphQLServerNotReadyException: The GraphQL server is not ready yet. Its initialization takes place in WordPress action hooks: 'wp_loaded' in the wp-admin, 'rest_api_init' in the WP REST API, and 'wp' otherwise (i.e. in the actual website). Retrieve the instance of the GraphQL server only after these hooks have been invoked.",
            "data": {
                "status": 500
            },
            "additional_errors": []
        }
        JSON;
    }

    protected function getExpectedResponseStatusCode(): int
    {
        return 500;
    }
}
