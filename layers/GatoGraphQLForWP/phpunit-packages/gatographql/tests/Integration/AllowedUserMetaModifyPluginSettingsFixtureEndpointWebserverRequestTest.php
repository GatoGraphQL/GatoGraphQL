<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class AllowedUserMetaModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use AllowedUserMetaFixtureEndpointWebserverRequestTestTrait;

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
        return 'gatographql_gatographql_schema-user-meta';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getAllowedUserMetaKeyEntriesNewValue();
    }
}
