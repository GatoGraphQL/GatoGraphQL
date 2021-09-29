<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CacheControlGraphQLQueryConfigurator;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * It is applied only to PersistedQuery
 */
class CacheControlSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface
{
    protected CacheControlGraphQLQueryConfigurator $cacheControlGraphQLQueryConfigurator;
    protected SchemaConfigCacheControlListBlock $schemaConfigCacheControlListBlock;

    #[Required]
    public function autowireCacheControlSchemaConfigurationExecuter(
        CacheControlGraphQLQueryConfigurator $cacheControlGraphQLQueryConfigurator,
        SchemaConfigCacheControlListBlock $schemaConfigCacheControlListBlock,
    ): void {
        $this->cacheControlGraphQLQueryConfigurator = $cacheControlGraphQLQueryConfigurator;
        $this->schemaConfigCacheControlListBlock = $schemaConfigCacheControlListBlock;
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

    protected function getBlock(): BlockInterface
    {
        return $this->schemaConfigCacheControlListBlock;
    }
}
