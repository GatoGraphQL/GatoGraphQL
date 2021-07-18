<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockAccessors;

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
     *
     * @return null|array<mixed> an array of 1 item: [$inheritQuery], or null if the post contains 0 or more than 1 block
     */
    public function getSinglePersistedQueryOptionsBlockAttributesFromPost(WP_Post $post): ?array
    {
        $persistedQueryEndpointAPIHierarchyBlock = $this->blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $post,
            $this->persistedQueryEndpointAPIHierarchyBlock
        );
        // If there is either 0 or more than 1, return nothing
        if ($persistedQueryEndpointAPIHierarchyBlock === null) {
            return null;
        }
        return [
            $persistedQueryEndpointAPIHierarchyBlock['attrs'][PersistedQueryEndpointAPIHierarchyBlock::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false,
        ];
    }
}
