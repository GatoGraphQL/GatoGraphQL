<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\GlobalFieldsSchemaExposure;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase;

abstract class AbstractSchemaGlobalFieldsModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::DEFAULT_SCHEMA_EXPOSURE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_global-fields';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES;
    }
}
