<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

abstract class AbstractNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Custom endpoint "unrestricted-schema" has no ACLs (so we get all the
     * fields when doing introspection), and "Namespacing" as "default"
     */
    protected function getEndpoint(): string
    {
        return 'graphql/unrestricted-schema/';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-namespacing';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return true;
    }
}
