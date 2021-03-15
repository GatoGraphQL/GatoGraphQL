<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLFieldDeprecationListCustomPostType;

class FieldDeprecationBlockCategory extends AbstractBlockCategory
{
    public const FIELD_DEPRECATION_BLOCK_CATEGORY = 'graphql-api-field-deprecation';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getPostTypes(): array
    {
        return [
            GraphQLFieldDeprecationListCustomPostType::CUSTOM_POST_TYPE,
        ];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::FIELD_DEPRECATION_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Field Deprecations for the GraphQL schema', 'graphql-api');
    }
}
