<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration\Upstream;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase;

abstract class AbstractNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * The single endpoint has "Namespacing" as "default"
     */
    protected static function getEndpoint(): string
    {
        return 'graphql';
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
