<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

/**
 * It comprises the endpoint and the persisted query CPTs
 */
class EndpointBlockCategory extends AbstractBlockCategory
{
    public const ENDPOINT_BLOCK_CATEGORY = 'graphql-api-query-exec';

    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType,
        protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
            $this->graphQLCustomEndpointCustomPostType->getCustomPostType(),
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
