<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

/**
 * Test the InternalGraphQLServer throws the GraphQLServerNotReady exception.
 *
 * @see layers/GraphQLAPIForWP/phpunit-plugins/graphql-api-for-wp-testing/src/Executers/InternalGraphQLServerTestExecuter.php
 */
class InternalGraphQLServerNotReadyQueryExecutionFixtureWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
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
            "message": "<p>There has been a critical error on this website.<\/p><p><a href=\"https:\/\/wordpress.org\/documentation\/article\/faq-troubleshooting\/\">Learn more about troubleshooting WordPress.<\/a><\/p>",
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
