<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class DefaultExposeAdminDataForSchemaConfigurationModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/power-users/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-expose-admin-data-for-schema-configuration';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-expose-admin-data';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
