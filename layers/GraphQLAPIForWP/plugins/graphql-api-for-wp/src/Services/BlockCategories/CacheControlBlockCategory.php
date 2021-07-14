<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;

class CacheControlBlockCategory extends AbstractBlockCategory
{
    public const CACHE_CONTROL_BLOCK_CATEGORY = 'graphql-api-cache-control';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getPostTypes(): array
    {
        /** @var GraphQLCacheControlListCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
        return [
            $customPostTypeService->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::CACHE_CONTROL_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Cache Control for GraphQL', 'graphql-api');
    }
}
