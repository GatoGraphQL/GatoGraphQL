<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\Environment as WebserverRequestsEnvironment;

class SchemaQueryExecutionFixtureWebserverRequestTest extends AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema';
    }

    /**
     * As the response contains the URL of the endpoint,
     * modify it so that it works for both "Integration Tests"
     * and "PROD Integration Tests", always printing the domain
     * from "Integration Tests" (as in the fixture .json file)
     */
    protected function adaptResponseBody(string $responseBody): string
    {
        return str_replace(
            WebserverRequestsEnvironment::getIntegrationTestsWebserverDomain(),
            'graphql-api.lndo.site',
            $responseBody
        );
    }
}
