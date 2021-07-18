<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;

class CustomEndpointBlockCategory extends AbstractBlockCategory
{
    public const CUSTOM_ENDPOINT_BLOCK_CATEGORY = 'graphql-api-endpoint';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
        return [
            $customPostTypeService->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::CUSTOM_ENDPOINT_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('GraphQL custom endpoint', 'graphql-api');
    }
}
