<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTagsBlock;
use PoPCMSSchema\Tags\Environment as TagsEnvironment;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaTagsBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaTagsBlock $schemaConfigTagsBlock = null;

    final public function setSchemaConfigSchemaTagsBlock(SchemaConfigSchemaTagsBlock $schemaConfigTagsBlock): void
    {
        $this->schemaConfigTagsBlock = $schemaConfigTagsBlock;
    }
    final protected function getSchemaConfigSchemaTagsBlock(): SchemaConfigSchemaTagsBlock
    {
        /** @var SchemaConfigSchemaTagsBlock */
        return $this->schemaConfigTagsBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaTagsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CATEGORIES;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $includedTagTaxonomies = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaTagsBlock::ATTRIBUTE_NAME_INCLUDED_TAG_TAXONOMIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            TagsModule::class,
            TagsEnvironment::QUERYABLE_TAG_TAXONOMIES
        );
        App::addFilter(
            $hookName,
            fn () => $includedTagTaxonomies,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaTagsBlock();
    }
}
