<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\CacheControlGraphQLQueryConfigurator;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\AbstractQueryExecutionSchemaConfigurator;

class PersistedQuerySchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    /**
     * Apply all the settings defined in the Schema Configuration:
     * - Access Control Lists
     * - Cache Control Lists
     * - Field Deprecation Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        parent::executeSchemaConfigurationItems($schemaConfigurationID);

        // Also execute the Cache Control
        $this->executeSchemaConfigurationCacheControlLists($schemaConfigurationID);
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Cache Control Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationCacheControlLists(int $schemaConfigurationID): void
    {
        // Do not execute Cache Control when previewing the query
        if (\is_preview()) {
            return;
        }
        // Check it is enabled by module
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(PerformanceFunctionalityModuleResolver::CACHE_CONTROL)) {
            return;
        }
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigCacheControlListBlock
         */
        $block = $instanceManager->getInstance(SchemaConfigCacheControlListBlock::class);
        $schemaConfigCCLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
        if (!is_null($schemaConfigCCLBlockDataItem)) {
            if ($cacheControlLists = $schemaConfigCCLBlockDataItem['attrs'][SchemaConfigCacheControlListBlock::ATTRIBUTE_NAME_CACHE_CONTROL_LISTS] ?? null) {
                $configurator = new CacheControlGraphQLQueryConfigurator();
                foreach ($cacheControlLists as $cacheControlListID) {
                    $configurator->executeSchemaConfiguration($cacheControlListID);
                }
            }
        }
    }
}
