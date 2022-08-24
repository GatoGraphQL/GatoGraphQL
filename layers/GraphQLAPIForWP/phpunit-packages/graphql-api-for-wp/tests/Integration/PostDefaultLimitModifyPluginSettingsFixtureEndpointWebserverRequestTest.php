<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class PostDefaultLimitModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-post-default-limit';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::LIST_DEFAULT_LIMIT;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-posts';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return 3;
    }
}
