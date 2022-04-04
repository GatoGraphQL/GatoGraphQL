<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;

class AccessControlBlockCategory extends AbstractBlockCategory
{
    public final const ACCESS_CONTROL_BLOCK_CATEGORY = 'graphql-api-access-control';

    private ?GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType = null;

    final public function setGraphQLAccessControlListCustomPostType(GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType): void
    {
        $this->graphQLAccessControlListCustomPostType = $graphQLAccessControlListCustomPostType;
    }
    final protected function getGraphQLAccessControlListCustomPostType(): GraphQLAccessControlListCustomPostType
    {
        return $this->graphQLAccessControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLAccessControlListCustomPostType()->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::ACCESS_CONTROL_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Access Control for GraphQL', 'graphql-api');
    }
}
