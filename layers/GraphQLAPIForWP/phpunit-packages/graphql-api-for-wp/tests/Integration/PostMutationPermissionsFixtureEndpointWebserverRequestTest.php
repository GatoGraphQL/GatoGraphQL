<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PHPUnitForGraphQLAPI\WebserverRequests\Environment;

class PostMutationPermissionsFixtureEndpointWebserverRequestTest extends AbstractChangeLoggedInUserModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        // This endpoint has "Support nested mutations?" as "Default"
        return 'graphql/website/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-post-mutation-permissions';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string|int $dataName): string
    {
        return 'graphqlapi_graphqlapi_nested-mutations';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS;
    }

    protected function getDifferentLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedAuthorUserUsername();
    }

    protected function getDifferentLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedAuthorUserPassword();
    }
}
