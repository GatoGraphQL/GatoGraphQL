<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use Symfony\Contracts\Service\Attribute\Required;

class CustomEndpointBlockCategory extends AbstractBlockCategory
{
    public const CUSTOM_ENDPOINT_BLOCK_CATEGORY = 'graphql-api-endpoint';
    protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType;

    #[Required]
    public function autowireCustomEndpointBlockCategory(
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ): void {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->graphQLCustomEndpointCustomPostType->getCustomPostType(),
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
