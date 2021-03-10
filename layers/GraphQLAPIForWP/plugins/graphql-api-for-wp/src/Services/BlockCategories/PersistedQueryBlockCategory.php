<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLPersistedQueryPostType;

class PersistedQueryBlockCategory extends AbstractBlockCategory
{
    public const PERSISTED_QUERY_BLOCK_CATEGORY = 'graphql-api-persisted-query';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getPostTypes(): array
    {
        return [
            GraphQLPersistedQueryPostType::POST_TYPE,
        ];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::PERSISTED_QUERY_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('GraphQL persisted query', 'graphql-api');
    }
}
