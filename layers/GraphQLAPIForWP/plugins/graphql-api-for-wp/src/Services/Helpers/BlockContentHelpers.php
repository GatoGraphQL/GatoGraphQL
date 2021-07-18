<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use WP_Post;

class BlockContentHelpers
{
    public function __construct(
        protected BlockHelpers $blockHelpers,
    ) {
    }
    /**
     * Extract the GraphiQL block attributes from the post
     *
     * @return null|array<mixed> An array of 2 items: [$query, $variables], or null if the post contains 0 or more than 1 block
     */
    public function getSingleGraphiQLBlockAttributesFromPost(WP_Post $post): ?array
    {
        // There must be only one block of type GraphiQL. Fetch it
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var PersistedQueryEndpointGraphiQLBlock
         */
        $block = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
        $graphiQLBlock = $this->blockHelpers->getSingleBlockOfTypeFromCustomPost(
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
}
