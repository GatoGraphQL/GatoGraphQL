<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback\BlockCategories;

use GraphQLAPI\SchemaFeedback\Services\PostTypes\GraphQLSchemaFeedbackListPostType;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;

class SchemaFeedbackBlockCategory extends AbstractBlockCategory
{
    public const SCHEMA_FEEDBACK_BLOCK_CATEGORY = 'graphql-api-schema-feedback';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string
     */
    public function getPostTypes(): array
    {
        return [
            GraphQLSchemaFeedbackListPostType::POST_TYPE,
        ];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::SCHEMA_FEEDBACK_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Schema Feedbacks for the GraphQL schema', 'graphql-api-schema-feedback');
    }
}
