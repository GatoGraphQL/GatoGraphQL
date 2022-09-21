<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPI\Integration\AbstractFixtureEndpointWebserverRequestTestCase;

class PassQueryViaURLParamQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-pass-query-via-url-param';
    }

    protected function getEndpoint(): string
    {
        /**
         * Add the query in the endpoint. If no query is passed via the body,
         * then this query will be executed.
         */
        return 'graphql/website/?query={ self { id } }';
    }

    /**
     * Do not send the GraphQL query in the body
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        // query. null => no passing GraphQL query in body
        $providerItems['query-wont-be-executed'][4] = '';
        return $providerItems;
    }

    protected function getMethod(): string
    {
        if ($this->dataName() === 'query-wont-be-executed') {
            return 'GET';
        }
        return parent::getMethod();
    }
}
