<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CacheControlGraphQLQueryConfigurator;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

/**
 * It is applied only to PersistedQuery
 */
class CacheControlSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        BlockHelpers $blockHelpers,
        protected CacheControlGraphQLQueryConfigurator $cacheControlGraphQLQueryConfigurator
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $blockHelpers,
        );
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        // Do not execute Cache Control when previewing the query
        if (\is_preview()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    public function getEnablingModule(): ?string
    {
        return PerformanceFunctionalityModuleResolver::CACHE_CONTROL;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigCCLBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if (!is_null($schemaConfigCCLBlockDataItem)) {
            if ($cacheControlLists = $schemaConfigCCLBlockDataItem['attrs'][SchemaConfigCacheControlListBlock::ATTRIBUTE_NAME_CACHE_CONTROL_LISTS] ?? null) {
                foreach ($cacheControlLists as $cacheControlListID) {
                    $this->cacheControlGraphQLQueryConfigurator->executeSchemaConfiguration($cacheControlListID);
                }
            }
        }
    }

    protected function getBlockClass(): string
    {
        return SchemaConfigCacheControlListBlock::class;
    }
}
