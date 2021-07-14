<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;

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
        /** @var GraphQLAccessControlListCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
        return [
            $customPostTypeService->getCustomPostType(),
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
