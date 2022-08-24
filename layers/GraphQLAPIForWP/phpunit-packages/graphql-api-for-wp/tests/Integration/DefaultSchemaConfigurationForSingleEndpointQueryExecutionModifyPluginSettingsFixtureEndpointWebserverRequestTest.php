<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class DefaultSchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    public const SCHEMA_CONFIGURATION_POWERUSERS_ID = 261;

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
        return ModuleSettingOptions::VALUE_FOR_SINGLE_ENDPOINT;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-configuration';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        // New value: Schema Config "Power Users"
        return self::SCHEMA_CONFIGURATION_POWERUSERS_ID;
    }
}
