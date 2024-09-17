<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class AllowedCustomPostMetaModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use AllowedCustomPostMetaFixtureEndpointWebserverRequestTestTrait;

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::ENTRIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-custompost-meta';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getAllowedCustomPostMetaKeyEntriesNewValue();
    }
}
