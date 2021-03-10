<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLAccessControlListPostType;

class AccessControlBlockCategory extends AbstractBlockCategory
{
    public const ACCESS_CONTROL_BLOCK_CATEGORY = 'graphql-api-access-control';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getPostTypes(): array
    {
        return [
            GraphQLAccessControlListPostType::POST_TYPE,
        ];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::ACCESS_CONTROL_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Access Control for GraphQL', 'graphql-api');
    }
}
