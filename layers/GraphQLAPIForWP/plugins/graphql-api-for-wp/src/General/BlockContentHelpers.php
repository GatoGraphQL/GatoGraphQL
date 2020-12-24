<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

use GraphQLAPI\GraphQLAPI\Blocks\PersistedQueryGraphiQLBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Blocks\PersistedQueryOptionsBlock;
use WP_Post;

class BlockContentHelpers
{
    /**
     * Extract the GraphiQL block attributes from the post
     *
     * @return null|array<mixed> An array of 2 items: [$query, $variables], or null if the post contains 0 or more than 1 block
     */
    public static function getSingleGraphiQLBlockAttributesFromPost(WP_Post $post): ?array
    {
        // There must be only one block of type GraphiQL. Fetch it
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var PersistedQueryGraphiQLBlock
         */
        $block = $instanceManager->getInstance(PersistedQueryGraphiQLBlock::class);
        $graphiQLBlock = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post,
            $block
        );
        // If there is either 0 or more than 1, return nothing
        if (is_null($graphiQLBlock)) {
            return null;
        }
        return [
            $graphiQLBlock['attrs'][PersistedQueryGraphiQLBlock::ATTRIBUTE_NAME_QUERY] ?? null,
            $graphiQLBlock['attrs'][PersistedQueryGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES] ?? null
        ];
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     *
     * @return null|array<mixed> an array of 1 item: [$inheritQuery], or null if the post contains 0 or more than 1 block
     */
    public static function getSinglePersistedQueryOptionsBlockAttributesFromPost(WP_Post $post): ?array
    {
        // There must be only one block of type PersistedQueryOptionsBlock. Fetch it
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var PersistedQueryOptionsBlock
         */
        $block = $instanceManager->getInstance(PersistedQueryOptionsBlock::class);
        $persistedQueryOptionsBlock = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post,
            $block
        );
        // If there is either 0 or more than 1, return nothing
        if (is_null($persistedQueryOptionsBlock)) {
            return null;
        }
        return [
            $persistedQueryOptionsBlock['attrs'][PersistedQueryOptionsBlock::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false,
        ];
    }
}
