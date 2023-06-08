<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

abstract class AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    public const SCHEMA_CONFIGURATION_WEBSITE_ID = 191;

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::SCHEMA_CONFIGURATION;
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        // New value: Schema Config "Website"
        return self::SCHEMA_CONFIGURATION_WEBSITE_ID;
    }
}
