<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPublicPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPrivatePersistedQueryEndpointCustomPostType;

class PersistedQueryEndpointBlockCategory extends AbstractBlockCategory
{
    public final const PERSISTED_QUERY_ENDPOINT_BLOCK_CATEGORY = 'graphql-api-persisted-query';

    private ?GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType = null;
    private ?GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLPublicPersistedQueryEndpointCustomPostType(GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPublicPersistedQueryEndpointCustomPostType = $graphQLPublicPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPublicPersistedQueryEndpointCustomPostType(): GraphQLPublicPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPublicPersistedQueryEndpointCustomPostType */
        return $this->graphQLPublicPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPublicPersistedQueryEndpointCustomPostType::class);
    }
    final public function setGraphQLPrivatePersistedQueryEndpointCustomPostType(GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPrivatePersistedQueryEndpointCustomPostType = $graphQLPrivatePersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPrivatePersistedQueryEndpointCustomPostType(): GraphQLPrivatePersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPrivatePersistedQueryEndpointCustomPostType */
        return $this->graphQLPrivatePersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPrivatePersistedQueryEndpointCustomPostType::class);
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLPublicPersistedQueryEndpointCustomPostType()->getCustomPostType(),
            $this->getGraphQLPrivatePersistedQueryEndpointCustomPostType()->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::PERSISTED_QUERY_ENDPOINT_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('GraphQL persisted query endpoint', 'graphql-api');
    }
}
