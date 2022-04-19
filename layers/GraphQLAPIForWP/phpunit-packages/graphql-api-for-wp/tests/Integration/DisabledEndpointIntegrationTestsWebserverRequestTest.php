<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractWebserverRequestTestCase;

class DisabledEndpointIntegrationTestsWebserverRequestTest extends AbstractWebserverRequestTestCase
{
    /**
     * Assert that the endpoints are disabled,
     * hence they return HTML, not JSON
     *
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $query = <<<GRAPHQL
            query {
                id
            }        
        GRAPHQL;
        return [
            'persisted-query' => [
                'text/html; charset=UTF-8',
                null,
                'graphql-query/website/',
                [],
                $query,
            ],
            'custom-endpoint' => [
                'text/html; charset=UTF-8',
                null,
                'graphql/customers/',
                [],
                $query,
            ],
        ];
    }
}
