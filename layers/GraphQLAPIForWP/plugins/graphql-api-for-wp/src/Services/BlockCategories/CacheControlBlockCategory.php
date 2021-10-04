<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use Symfony\Contracts\Service\Attribute\Required;

class CacheControlBlockCategory extends AbstractBlockCategory
{
    public const CACHE_CONTROL_BLOCK_CATEGORY = 'graphql-api-cache-control';

    protected GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType;

    #[Required]
    final public function autowireCacheControlBlockCategory(
        GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType,
    ): void {
        $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->graphQLCacheControlListCustomPostType->getCustomPostType(),
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
