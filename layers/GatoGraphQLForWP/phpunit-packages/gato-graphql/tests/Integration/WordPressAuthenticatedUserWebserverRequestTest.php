<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that the authenticated user can access the GraphQL endpoints.
 */
class WordPressAuthenticatedUserWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * @return array<string,array<mixed>>
     */
    public static function provideEndpointEntries(): array
    {
        $query = static::getGraphQLQuery();
        $expectedResponseBody = static::getGraphQLExpectedResponse();
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

    protected static function getGraphQLQuery(): string
    {
        return <<<GRAPHQL
            {
                id
            }       
        GRAPHQL;
    }

    protected static function getGraphQLExpectedResponse(): string
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
