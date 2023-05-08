<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

/**
 * @todo This test doesn't make sense anymore, remove!!!
 *
 * Test the InternalGraphQLServer throws the GraphQLServerNotReady exception.
 *
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Executers/InternalGraphQLServerTestExecuter.php
 */
class InternalGraphQLServerNotReadyQueryExecutionFixtureWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    use InternalGraphQLServerWebserverRequestTestTrait;

    protected function getEndpoint(): string
    {
        return $this->getInternalGraphQLServerEndpoint(
            'graphql/',
            [
                'withDeepNested' => false,
                'withNotReady' => true,
            ]
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
            "data": {
                "id": "root"
            }
        }
        JSON;
    }
}
