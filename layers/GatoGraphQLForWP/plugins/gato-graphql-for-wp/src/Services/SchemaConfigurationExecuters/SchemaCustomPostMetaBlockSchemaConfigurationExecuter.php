<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaCustomPostMetaBlock;
use PoPCMSSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPCMSSchema\CustomPostMeta\Module as CustomPostMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCustomPostMetaBlockSchemaConfigurationExecuter extends AbstractSchemaMetaBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaCustomPostMetaBlock $schemaConfigCustomPostMetaBlock = null;

    final public function setSchemaConfigSchemaCustomPostMetaBlock(SchemaConfigSchemaCustomPostMetaBlock $schemaConfigCustomPostMetaBlock): void
    {
        $this->schemaConfigCustomPostMetaBlock = $schemaConfigCustomPostMetaBlock;
    }
    final protected function getSchemaConfigSchemaCustomPostMetaBlock(): SchemaConfigSchemaCustomPostMetaBlock
    {
        /** @var SchemaConfigSchemaCustomPostMetaBlock */
        return $this->schemaConfigCustomPostMetaBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaCustomPostMetaBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            CustomPostMetaModule::class,
            CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            CustomPostMetaModule::class,
            CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCustomPostMetaBlock();
    }
}
