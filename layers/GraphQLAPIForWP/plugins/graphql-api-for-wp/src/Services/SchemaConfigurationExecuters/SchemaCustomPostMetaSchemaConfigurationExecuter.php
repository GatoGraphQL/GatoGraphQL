<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCustomPostMetaBlock;
use PoPCMSSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPCMSSchema\CustomPostMeta\Module as CustomPostMetaModule;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCustomPostMetaSchemaConfigurationExecuter extends AbstractCustomizableConfigurationSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaCustomPostMetaBlock $schemaConfigCustomPostsBlock = null;

    final public function setSchemaConfigSchemaCustomPostMetaBlock(SchemaConfigSchemaCustomPostMetaBlock $schemaConfigCustomPostsBlock): void
    {
        $this->schemaConfigCustomPostsBlock = $schemaConfigCustomPostsBlock;
    }
    final protected function getSchemaConfigSchemaCustomPostMetaBlock(): SchemaConfigSchemaCustomPostMetaBlock
    {
        /** @var SchemaConfigSchemaCustomPostMetaBlock */
        return $this->schemaConfigCustomPostsBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaCustomPostMetaBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META;
    }

    protected function doExecuteSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            return;
        }
        $entries = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCustomPostMetaBlock::ATTRIBUTE_NAME_ENTRIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CustomPostMetaModule::class,
            CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES
        );
        \add_filter(
            $hookName,
            fn () => $entries,
            PHP_INT_MAX
        );
        $behavior = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCustomPostMetaBlock::ATTRIBUTE_NAME_BEHAVIOR] ?? $this->getDefaultBehavior();
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CustomPostMetaModule::class,
            CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR
        );
        \add_filter(
            $hookName,
            fn () => $behavior,
            PHP_INT_MAX
        );
    }

    protected function getDefaultBehavior(): string
    {
        return PluginEnvironment::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCustomPostMetaBlock();
    }
}
