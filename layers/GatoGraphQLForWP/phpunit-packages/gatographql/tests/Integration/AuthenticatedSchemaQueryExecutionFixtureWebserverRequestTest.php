<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment as WebserverRequestsEnvironment;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Fetching schema data that only an administrator may read: the value of
 * protected options and protected user meta keys (e.g. `wp_capabilities`).
 * Administrators bypass the read restrictions, so the data is returned.
 * (Non-administrators being denied is asserted by the dedicated
 * option/meta read-protection tests.)
 */
class AuthenticatedSchemaQueryExecutionFixtureWebserverRequestTest extends AbstractSingleEndpointQueryExecutionFixtureWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-authenticated-schema';
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
            'gatographql.lndo.site',
            $responseBody
        );
    }
}
