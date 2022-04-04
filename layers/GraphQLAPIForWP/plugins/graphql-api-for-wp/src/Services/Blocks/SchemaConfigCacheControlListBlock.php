<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;

/**
 * Cache Control block
 */
class SchemaConfigCacheControlListBlock extends AbstractSchemaConfigCustomPostListBlock
{
    use MainPluginBlockTrait;

    public final const ATTRIBUTE_NAME_CACHE_CONTROL_LISTS = 'cacheControlLists';

    private ?GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType = null;

    final public function setGraphQLCacheControlListCustomPostType(GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType): void
    {
        $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
    }
    final protected function getGraphQLCacheControlListCustomPostType(): GraphQLCacheControlListCustomPostType
    {
        return $this->graphQLCacheControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
    }

    protected function getBlockName(): string
    {
        return 'schema-config-cache-control-lists';
    }

    public function getBlockPriority(): int
    {
        return 190;
    }

    public function getEnablingModule(): ?string
    {
        return PerformanceFunctionalityModuleResolver::CACHE_CONTROL;
    }

    protected function getAttributeName(): string
    {
        return self::ATTRIBUTE_NAME_CACHE_CONTROL_LISTS;
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLCacheControlListCustomPostType()->getCustomPostType();
    }

    protected function getHeader(): string
    {
        return \__('Cache Control Lists:');
    }
}
