<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;

abstract class AbstractTreatMenuItemRawTitleAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-menuitem-raw-title-as-sensitive-data';
    }

    protected function getSettingsKey(): string
    {
        return SchemaTypeModuleResolver::OPTION_TREAT_MENUITEM_RAW_TITLE_AS_SENSITIVE_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-menus';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
