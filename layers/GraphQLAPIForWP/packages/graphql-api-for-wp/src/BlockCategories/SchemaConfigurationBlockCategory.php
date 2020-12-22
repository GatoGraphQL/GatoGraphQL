<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\BlockCategories;

use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;

class SchemaConfigurationBlockCategory extends AbstractBlockCategory
{
    public const SCHEMA_CONFIGURATION_BLOCK_CATEGORY = 'graphql-api-schema-config';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getPostTypes(): array
    {
        return [
            GraphQLSchemaConfigurationPostType::POST_TYPE,
        ];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::SCHEMA_CONFIGURATION_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Schema Configuration for GraphQL', 'graphql-api');
    }
}
