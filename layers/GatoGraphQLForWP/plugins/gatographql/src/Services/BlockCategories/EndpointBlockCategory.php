<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\BlockCategories;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;

/**
 * It comprises the endpoint and the persisted query CPTs
 */
class EndpointBlockCategory extends AbstractBlockCategory
{
    public final const ENDPOINT_BLOCK_CATEGORY = 'gatographql-query-exec';

    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        if ($this->graphQLCustomEndpointCustomPostType === null) {
            /** @var GraphQLCustomEndpointCustomPostType */
            $graphQLCustomEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
            $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        }
        return $this->graphQLCustomEndpointCustomPostType;
    }
    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        if ($this->graphQLPersistedQueryEndpointCustomPostType === null) {
            /** @var GraphQLPersistedQueryEndpointCustomPostType */
            $graphQLPersistedQueryEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
            $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
        }
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
            $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType(),
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
        return __('Query execution (endpoint/persisted query)', 'gatographql');
    }
}
