<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCustomPostsBlock;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCustomPostsBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaCustomPostsBlock $schemaConfigSchemaCustomPostsBlock = null;

    final public function setSchemaConfigSchemaCustomPostsBlock(SchemaConfigSchemaCustomPostsBlock $schemaConfigSchemaCustomPostsBlock): void
    {
        $this->schemaConfigSchemaCustomPostsBlock = $schemaConfigSchemaCustomPostsBlock;
    }
    final protected function getSchemaConfigSchemaCustomPostsBlock(): SchemaConfigSchemaCustomPostsBlock
    {
        /** @var SchemaConfigSchemaCustomPostsBlock */
        return $this->schemaConfigSchemaCustomPostsBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaCustomPostsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $includedCustomPostTypes = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCustomPostsBlock::ATTRIBUTE_NAME_INCLUDED_CUSTOM_POST_TYPES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CustomPostsModule::class,
            CustomPostsEnvironment::QUERYABLE_CUSTOMPOST_TYPES
        );
        App::addFilter(
            $hookName,
            fn () => $includedCustomPostTypes,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCustomPostsBlock();
    }
}
