<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockAccessors;

use GraphQLAPI\GraphQLAPI\GetterSetterObjects\Blocks\PersistedQueryEndpointAPIHierarchyBlockDataObject;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use WP_Post;

class PersistedQueryEndpointAPIHierarchyBlockAccessor
{
    public function __construct(
        protected BlockHelpers $blockHelpers,
        protected PersistedQueryEndpointAPIHierarchyBlock $persistedQueryEndpointAPIHierarchyBlock,
    ) {
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     */
    public function getSinglePersistedQueryOptionsBlockAttributesFromPost(WP_Post $post): ?PersistedQueryEndpointAPIHierarchyBlockDataObject
    {
        $persistedQueryEndpointAPIHierarchyBlock = $this->blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $post,
            $this->persistedQueryEndpointAPIHierarchyBlock
        );
        // If there is either 0 or more than 1, return nothing
        if ($persistedQueryEndpointAPIHierarchyBlock === null) {
            return null;
        }
        return new PersistedQueryEndpointAPIHierarchyBlockDataObject(
            $persistedQueryEndpointAPIHierarchyBlock['attrs'][PersistedQueryEndpointAPIHierarchyBlock::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false,
        );
    }
}
