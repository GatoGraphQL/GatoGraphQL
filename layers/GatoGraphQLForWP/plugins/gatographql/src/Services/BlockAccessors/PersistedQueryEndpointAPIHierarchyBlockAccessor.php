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
        if ($this->blockHelpers === null) {
            /** @var BlockHelpers */
            $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
            $this->blockHelpers = $blockHelpers;
        }
        return $this->blockHelpers;
    }
    final public function setPersistedQueryEndpointAPIHierarchyBlock(PersistedQueryEndpointAPIHierarchyBlock $persistedQueryEndpointAPIHierarchyBlock): void
    {
        $this->persistedQueryEndpointAPIHierarchyBlock = $persistedQueryEndpointAPIHierarchyBlock;
    }
    final protected function getPersistedQueryEndpointAPIHierarchyBlock(): PersistedQueryEndpointAPIHierarchyBlock
    {
        if ($this->persistedQueryEndpointAPIHierarchyBlock === null) {
            /** @var PersistedQueryEndpointAPIHierarchyBlock */
            $persistedQueryEndpointAPIHierarchyBlock = $this->instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);
            $this->persistedQueryEndpointAPIHierarchyBlock = $persistedQueryEndpointAPIHierarchyBlock;
        }
        return $this->persistedQueryEndpointAPIHierarchyBlock;
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
