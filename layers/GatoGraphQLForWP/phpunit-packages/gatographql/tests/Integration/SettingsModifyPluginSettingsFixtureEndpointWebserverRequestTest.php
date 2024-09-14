<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class SettingsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-settings';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::BEHAVIOR;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-settings';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return Behaviors::ALLOW;
    }
}
