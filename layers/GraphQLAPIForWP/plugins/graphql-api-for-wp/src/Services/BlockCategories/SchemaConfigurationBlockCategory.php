<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaConfigurationBlockCategory extends AbstractBlockCategory
{
    public const SCHEMA_CONFIGURATION_BLOCK_CATEGORY = 'graphql-api-schema-config';

    protected GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType;

    #[Required]
    public function autowireSchemaConfigurationBlockCategory(
        GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType,
    ) {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
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
