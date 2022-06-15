<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\Environment;
use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

class MyContentQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;
    
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-my-content';
    }

    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedContributorUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedContributorUserPassword();
    }
}
