<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that the authenticated user can access the GraphQL endpoints.
 */
abstract class AbstractWordPressAuthenticatedUserWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;
    
    /**
     * @return array<string,string>
     */
    abstract protected static function getWordPressAuthenticatedUserEndpoints(): array;

    /**
     * @return array<string,array<mixed>>
     */
    public static function provideEndpointEntries(): array
    {
        $query = static::getGraphQLQuery();
        $expectedResponseBody = static::getGraphQLExpectedResponse();
        $entries = [];
        foreach (static::getWordPressAuthenticatedUserEndpoints() as $dataName => $endpoint) {
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
