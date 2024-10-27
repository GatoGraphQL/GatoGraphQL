<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaUserMetaBlock;
use PoPCMSSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPCMSSchema\UserMeta\Module as UserMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaUserMetaBlockSchemaConfigurationExecuter extends AbstractSchemaMetaBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaUserMetaBlock $schemaConfigSchemaUserMetaBlock = null;

    final protected function getSchemaConfigSchemaUserMetaBlock(): SchemaConfigSchemaUserMetaBlock
    {
        if ($this->schemaConfigSchemaUserMetaBlock === null) {
            /** @var SchemaConfigSchemaUserMetaBlock */
            $schemaConfigSchemaUserMetaBlock = $this->instanceManager->getInstance(SchemaConfigSchemaUserMetaBlock::class);
            $this->schemaConfigSchemaUserMetaBlock = $schemaConfigSchemaUserMetaBlock;
        }
        return $this->schemaConfigSchemaUserMetaBlock;
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_USER_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            UserMetaModule::class,
            UserMetaEnvironment::USER_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            UserMetaModule::class,
            UserMetaEnvironment::USER_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaUserMetaBlock();
    }
}
