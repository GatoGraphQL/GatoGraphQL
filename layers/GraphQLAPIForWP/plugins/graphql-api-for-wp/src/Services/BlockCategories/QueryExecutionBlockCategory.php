<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostType;

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
        /** @var GraphQLPersistedQueryCustomPostType */
        $persistedQueryCustomPostTypeService = $this->instanceManager->getInstance(GraphQLPersistedQueryCustomPostType::class);
        /** @var GraphQLEndpointCustomPostType */
        $endpointCustomPostTypeService = $this->instanceManager->getInstance(GraphQLEndpointCustomPostType::class);
        return [
            $persistedQueryCustomPostTypeService->getCustomPostType(),
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
