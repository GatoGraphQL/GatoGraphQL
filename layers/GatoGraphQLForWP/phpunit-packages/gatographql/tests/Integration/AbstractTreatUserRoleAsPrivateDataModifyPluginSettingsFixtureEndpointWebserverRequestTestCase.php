<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;

abstract class AbstractTreatUserRoleAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getSettingsKey(): string
    {
        return SchemaTypeModuleResolver::OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-user-roles';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
