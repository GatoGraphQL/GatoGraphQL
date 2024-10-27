<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaSettingsBlock;
use PoPCMSSchema\Settings\Environment as SettingsEnvironment;
use PoPCMSSchema\Settings\Module as SettingsModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaSettingsBlockSchemaConfigurationExecuter extends AbstractSchemaAllowAccessToEntriesBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaSettingsBlock $schemaConfigSchemaSettingsBlock = null;

    final protected function getSchemaConfigSchemaSettingsBlock(): SchemaConfigSchemaSettingsBlock
    {
        if ($this->schemaConfigSchemaSettingsBlock === null) {
            /** @var SchemaConfigSchemaSettingsBlock */
            $schemaConfigSchemaSettingsBlock = $this->instanceManager->getInstance(SchemaConfigSchemaSettingsBlock::class);
            $this->schemaConfigSchemaSettingsBlock = $schemaConfigSchemaSettingsBlock;
        }
        return $this->schemaConfigSchemaSettingsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SETTINGS;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            SettingsModule::class,
            SettingsEnvironment::SETTINGS_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            SettingsModule::class,
            SettingsEnvironment::SETTINGS_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaSettingsBlock();
    }
}
