<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class DefaultSchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    public const SCHEMA_CONFIGURATION_WEBSITE_ID = 191;

    /**
     * Single endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-single-endpoint';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::SCHEMA_CONFIGURATION;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_single-endpoint';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        // New value: Schema Config "Website"
        return self::SCHEMA_CONFIGURATION_WEBSITE_ID;
    }
}
