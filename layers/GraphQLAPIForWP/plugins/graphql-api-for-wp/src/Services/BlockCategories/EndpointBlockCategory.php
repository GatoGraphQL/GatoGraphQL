<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;

/**
 * It comprises the endpoint and the persisted query CPTs
 */
class EndpointBlockCategory extends AbstractBlockCategory
{
    public const ENDPOINT_BLOCK_CATEGORY = 'graphql-api-query-exec';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $persistedQueryEndpointCustomPostTypeService = $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        /** @var GraphQLCustomEndpointCustomPostType */
        $customEndpointCustomPostTypeService = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
        return [
            $persistedQueryEndpointCustomPostTypeService->getCustomPostType(),
            $customEndpointCustomPostTypeService->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::ENDPOINT_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Query execution (endpoint/persisted query)', 'graphql-api');
    }
}
