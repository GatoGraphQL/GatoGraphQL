<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;

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
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
        return [
            $customPostTypeService->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::SCHEMA_CONFIGURATION_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Schema Configuration for GraphQL', 'graphql-api');
    }
}
