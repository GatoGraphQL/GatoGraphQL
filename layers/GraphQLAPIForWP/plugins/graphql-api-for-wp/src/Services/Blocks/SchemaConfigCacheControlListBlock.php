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

    public const ATTRIBUTE_NAME_CACHE_CONTROL_LISTS = 'cacheControlLists';

    protected function getBlockName(): string
    {
        return 'schema-config-cache-control-lists';
    }

    public function getSchemaConfigBlockPriority(): int
    {
        return 90;
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
        /** @var GraphQLCacheControlListCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
        return $customPostTypeService->getCustomPostType();
    }

    protected function getHeader(): string
    {
        return \__('Cache Control Lists:');
    }
}
