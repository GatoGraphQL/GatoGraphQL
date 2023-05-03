<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCategoriesBlock;
use PoPCMSSchema\Categories\Environment as CategoriesEnvironment;
use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCategoriesBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaCategoriesBlock $schemaConfigCategoriesBlock = null;

    final public function setSchemaConfigSchemaCategoriesBlock(SchemaConfigSchemaCategoriesBlock $schemaConfigCategoriesBlock): void
    {
        $this->schemaConfigCategoriesBlock = $schemaConfigCategoriesBlock;
    }
    final protected function getSchemaConfigSchemaCategoriesBlock(): SchemaConfigSchemaCategoriesBlock
    {
        /** @var SchemaConfigSchemaCategoriesBlock */
        return $this->schemaConfigCategoriesBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaCategoriesBlock::class);
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
        $includedCategoryTaxonomies = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCategoriesBlock::ATTRIBUTE_NAME_INCLUDED_CATEGORY_TAXONOMIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CategoriesModule::class,
            CategoriesEnvironment::QUERYABLE_CATEGORY_TAXONOMIES
        );
        App::addFilter(
            $hookName,
            fn () => $includedCategoryTaxonomies,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCategoriesBlock();
    }
}
