<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;

/**
 * It comprises the endpoint and the persisted query CPTs
 */
class QueryExecutionBlockCategory extends AbstractBlockCategory
{
    public const QUERY_EXECUTION_BLOCK_CATEGORY = 'graphql-api-query-exec';

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
        $endpointCustomPostTypeService = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
        return [
            $persistedQueryEndpointCustomPostTypeService->getCustomPostType(),
            $endpointCustomPostTypeService->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::QUERY_EXECUTION_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Query execution (endpoint/persisted query)', 'graphql-api');
    }
}
