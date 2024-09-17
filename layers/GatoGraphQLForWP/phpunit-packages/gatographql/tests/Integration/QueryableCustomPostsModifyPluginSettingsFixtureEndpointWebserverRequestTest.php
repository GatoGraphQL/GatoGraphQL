<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class QueryableCustomPostsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use QueryableCustomPostsFixtureEndpointWebserverRequestTestTrait;

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::CUSTOMPOST_TYPES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-customposts';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getIncludedCustomPostTypesNewValue();
    }
}
