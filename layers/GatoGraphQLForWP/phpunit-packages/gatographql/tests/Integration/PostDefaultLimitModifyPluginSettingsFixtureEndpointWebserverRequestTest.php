<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class PostDefaultLimitModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-post-default-limit';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::LIST_DEFAULT_LIMIT;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-posts';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return 3;
    }
}
