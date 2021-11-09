<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CacheControlGraphQLQueryConfigurator;

/**
 * It is applied only to PersistedQuery
 */
class CacheControlSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?CacheControlGraphQLQueryConfigurator $cacheControlGraphQLQueryConfigurator = null;
    private ?SchemaConfigCacheControlListBlock $schemaConfigCacheControlListBlock = null;

    final public function setCacheControlGraphQLQueryConfigurator(CacheControlGraphQLQueryConfigurator $cacheControlGraphQLQueryConfigurator): void
    {
        $this->cacheControlGraphQLQueryConfigurator = $cacheControlGraphQLQueryConfigurator;
    }
    final protected function getCacheControlGraphQLQueryConfigurator(): CacheControlGraphQLQueryConfigurator
    {
        return $this->cacheControlGraphQLQueryConfigurator ??= $this->instanceManager->getInstance(CacheControlGraphQLQueryConfigurator::class);
    }
    final public function setSchemaConfigCacheControlListBlock(SchemaConfigCacheControlListBlock $schemaConfigCacheControlListBlock): void
    {
        $this->schemaConfigCacheControlListBlock = $schemaConfigCacheControlListBlock;
    }
    final protected function getSchemaConfigCacheControlListBlock(): SchemaConfigCacheControlListBlock
    {
        return $this->schemaConfigCacheControlListBlock ??= $this->instanceManager->getInstance(SchemaConfigCacheControlListBlock::class);
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
                    $this->getCacheControlGraphQLQueryConfigurator()->executeSchemaConfiguration($cacheControlListID);
                }
            }
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigCacheControlListBlock();
    }
}
