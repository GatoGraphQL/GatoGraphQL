<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

class MyContentQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-my-content';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedContributorUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedContributorUserPassword();
    }

    /**
     * As the response contains the URL of the endpoint,
     * modify it so that it works for both "Integration Tests"
     * and "PROD Integration Tests", always printing the domain
     * from "Integration Tests" (as in the fixture .json file)
     */
    protected function adaptResponseBody(string $responseBody): string
    {
        return match (static::getDataName()) {
            'media-items' => str_replace(
                Environment::getIntegrationTestsWebserverDomain(),
                'gatographql.lndo.site',
                $responseBody
            ),
            default => parent::adaptResponseBody($responseBody),
        };
    }
}
