<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class DefaultExposeSensitiveDataForSchemaConfigurationModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        // This endpoint has "Expose sensitive data in the schema" as "Default"
        return 'graphql/mobile-app/';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-expose-sensitive-data-for-schema-configuration';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-expose-sensitive-data';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
