<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;

class SchemaConfigurationBlockCategory extends AbstractBlockCategory
{
    public final const SCHEMA_CONFIGURATION_BLOCK_CATEGORY = 'graphql-api-schema-config';

    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        return $this->graphQLSchemaConfigurationCustomPostType ??= $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
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
