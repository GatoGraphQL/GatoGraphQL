<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class SpecCompliantErrorHandlingModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-spec-compliant-error-handling';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_spec-compliant-error-handling';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return true;
    }
}
