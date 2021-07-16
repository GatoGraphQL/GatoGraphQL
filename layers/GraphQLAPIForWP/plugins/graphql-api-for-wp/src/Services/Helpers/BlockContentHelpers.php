<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use WP_Post;

class BlockContentHelpers
{
    /**
     * Extract the GraphiQL block attributes from the post
     *
     * @return null|array<mixed> An array of 2 items: [$query, $variables], or null if the post contains 0 or more than 1 block
     */
    public function getSingleGraphiQLBlockAttributesFromPost(WP_Post $post): ?array
    {
        // There must be only one block of type GraphiQL. Fetch it
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var BlockHelpers */
        $blockHelpers = $instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var PersistedQueryEndpointGraphiQLBlock
         */
        $block = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
        $graphiQLBlock = $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $post,
            $block
        );
        // If there is either 0 or more than 1, return nothing
        if (is_null($graphiQLBlock)) {
            return null;
        }
        return [
            $graphiQLBlock['attrs'][PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_QUERY] ?? null,
            $graphiQLBlock['attrs'][PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES] ?? null
        ];
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     *
     * @return null|array<mixed> an array of 1 item: [$inheritQuery], or null if the post contains 0 or more than 1 block
     */
    public function getSinglePersistedQueryOptionsBlockAttributesFromPost(WP_Post $post): ?array
    {
        // There must be only one block of type PersistedQueryEndpointAPIHierarchyBlock. Fetch it
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var BlockHelpers */
        $blockHelpers = $instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var PersistedQueryEndpointAPIHierarchyBlock
         */
        $block = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);
        $persistedQueryEndpointAPIHierarchyBlock = $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $post,
            $block
        );
        // If there is either 0 or more than 1, return nothing
        if (is_null($persistedQueryEndpointAPIHierarchyBlock)) {
            return null;
        }
        return [
            $persistedQueryEndpointAPIHierarchyBlock['attrs'][PersistedQueryEndpointAPIHierarchyBlock::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false,
        ];
    }
}
