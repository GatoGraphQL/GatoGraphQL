<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;

class CacheControlBlockCategory extends AbstractBlockCategory
{
    public final const CACHE_CONTROL_BLOCK_CATEGORY = 'graphql-api-cache-control';

    private ?GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType = null;

    final public function setGraphQLCacheControlListCustomPostType(GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType): void
    {
        $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
    }
    final protected function getGraphQLCacheControlListCustomPostType(): GraphQLCacheControlListCustomPostType
    {
        return $this->graphQLCacheControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLCacheControlListCustomPostType()->getCustomPostType(),
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
