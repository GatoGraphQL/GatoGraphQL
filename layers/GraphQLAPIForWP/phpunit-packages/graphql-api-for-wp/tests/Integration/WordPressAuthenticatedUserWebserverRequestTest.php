<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that the authenticated user can access the GraphQL endpoints.
 */
class WordPressAuthenticatedUserWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $query = $this->getGraphQLQuery();
        $expectedResponseBody = $this->getGraphQLExpectedResponse();
        $entries = [];
        foreach (WordPressAuthenticatedUserEndpoints::ENDPOINTS as $dataName => $endpoint) {
            $entries[$dataName] = [
                'application/json',
                $expectedResponseBody,
                $endpoint,
                [],
                $query,
            ];
        }
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
                "data": {
                    "id": "root"
                }
            }
        JSON;
    }
}
