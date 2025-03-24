<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver;

abstract class AbstractTreatMetaKeysAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getSettingsKey(): string
    {
        return MetaSchemaTypeModuleResolver::OPTION_TREAT_META_KEYS_AS_SENSITIVE_DATA;
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
