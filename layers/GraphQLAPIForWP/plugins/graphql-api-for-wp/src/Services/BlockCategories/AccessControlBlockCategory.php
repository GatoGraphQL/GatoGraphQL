<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class AccessControlBlockCategory extends AbstractBlockCategory
{
    public const ACCESS_CONTROL_BLOCK_CATEGORY = 'graphql-api-access-control';

    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType,
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
            $this->graphQLAccessControlListCustomPostType->getCustomPostType(),
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
