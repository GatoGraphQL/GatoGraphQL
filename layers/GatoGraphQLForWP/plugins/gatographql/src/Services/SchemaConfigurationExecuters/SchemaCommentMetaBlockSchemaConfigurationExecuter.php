<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaCommentMetaBlock;
use PoPCMSSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPCMSSchema\CommentMeta\Module as CommentMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCommentMetaBlockSchemaConfigurationExecuter extends AbstractSchemaMetaBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaCommentMetaBlock $schemaConfigSchemaCommentMetaBlock = null;

    final protected function getSchemaConfigSchemaCommentMetaBlock(): SchemaConfigSchemaCommentMetaBlock
    {
        if ($this->schemaConfigSchemaCommentMetaBlock === null) {
            /** @var SchemaConfigSchemaCommentMetaBlock */
            $schemaConfigSchemaCommentMetaBlock = $this->instanceManager->getInstance(SchemaConfigSchemaCommentMetaBlock::class);
            $this->schemaConfigSchemaCommentMetaBlock = $schemaConfigSchemaCommentMetaBlock;
        }
        return $this->schemaConfigSchemaCommentMetaBlock;
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            CommentMetaModule::class,
            CommentMetaEnvironment::COMMENT_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            CommentMetaModule::class,
            CommentMetaEnvironment::COMMENT_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCommentMetaBlock();
    }
}
