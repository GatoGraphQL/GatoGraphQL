<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\BlockAccessors;

use GatoGraphQL\GatoGraphQL\AppObjects\BlockAttributes\PersistedQueryEndpointAPIHierarchyBlockAttributes;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockHelpers;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

class PersistedQueryEndpointAPIHierarchyBlockAccessor
{
    use BasicServiceTrait;

    private ?BlockHelpers $blockHelpers = null;
    private ?PersistedQueryEndpointAPIHierarchyBlock $persistedQueryEndpointAPIHierarchyBlock = null;

    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        /** @var BlockHelpers */
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }
    final public function setPersistedQueryEndpointAPIHierarchyBlock(PersistedQueryEndpointAPIHierarchyBlock $persistedQueryEndpointAPIHierarchyBlock): void
    {
        $this->persistedQueryEndpointAPIHierarchyBlock = $persistedQueryEndpointAPIHierarchyBlock;
    }
    final protected function getPersistedQueryEndpointAPIHierarchyBlock(): PersistedQueryEndpointAPIHierarchyBlock
    {
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        return $this->persistedQueryEndpointAPIHierarchyBlock ??= $this->instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     */
    public function getAttributes(WP_Post $post): ?PersistedQueryEndpointAPIHierarchyBlockAttributes
    {
        $apiHierarchyBlock = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $post,
            $this->getPersistedQueryEndpointAPIHierarchyBlock()
        );
        // If there is either 0 or more than 1, return nothing
        if ($apiHierarchyBlock === null) {
            return null;
        }
        return new PersistedQueryEndpointAPIHierarchyBlockAttributes(
            $apiHierarchyBlock['attrs'][PersistedQueryEndpointAPIHierarchyBlock::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false,
        );
    }
}
