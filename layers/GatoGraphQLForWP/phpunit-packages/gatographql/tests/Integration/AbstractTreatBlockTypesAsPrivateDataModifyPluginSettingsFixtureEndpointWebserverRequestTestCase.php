<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;

abstract class AbstractTreatBlockTypesAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-block-types-as-sensitive-data';
    }

    protected function getSettingsKey(): string
    {
        return SchemaTypeModuleResolver::OPTION_TREAT_BLOCK_TYPES_AS_SENSITIVE_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-blocks';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
