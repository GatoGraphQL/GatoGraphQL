<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractBlock;
use WP_Post;

class BlockHelpers
{
    /**
     * After parsing a post, cache its blocks
     *
     * @var array<int, array>
     */
    protected array $blockCache = [];

    /**
     * Extract the blocks from the post
     *
     * @param WP_Post|int $configurationPostOrID
     * @return array<string, mixed> The block stores its data as property => value
     */
    public function getBlocksFromCustomPost(
        $configurationPostOrID
    ): array {
        if (\is_object($configurationPostOrID)) {
            $configurationPost = $configurationPostOrID;
            $configurationPostID = $configurationPost->ID;
        } else {
            $configurationPostID = $configurationPostOrID;
            $configurationPost = \get_post($configurationPostID);
        }
        // If there's either no post or ID, then that object doesn't exist (or maybe it's draft or trashed)
        if (is_null($configurationPost) || !$configurationPostID) {
            return [];
        }
        // If it's trashed, then do not use
        if ($configurationPost->post_status == 'trash') {
            return [];
        }

        // Get the blocks from the inner cache, if available
        if (isset($this->blockCache[$configurationPostID])) {
            $blocks = $this->blockCache[$configurationPostID];
        } else {
            $blocks = \parse_blocks($configurationPost->post_content);
            $this->blockCache[$configurationPostID] = $blocks;
        }

        return $blocks;
    }

    /**
     * Read the configuration post, and extract the configuration, contained through the specified block
     *
     * @param WP_Post|int $configurationPostOrID
     * @return array<array> A list of block data, each as an array
     */
    public function getBlocksOfTypeFromCustomPost(
        $configurationPostOrID,
        AbstractBlock $block
    ): array {
        $blocks = $this->getBlocksFromCustomPost($configurationPostOrID);

        // Obtain the blocks for the provided block type
        $blockFullName = $block->getBlockFullName();
        return array_values(array_filter(
            $blocks,
            fn ($block) => $block['blockName'] == $blockFullName
        ));
    }

    /**
     * Read the single block of a certain type, contained in the post.
     * If there are more than 1, or none, return null
     *
     * @param WP_Post|int $configurationPostOrID
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    public function getSingleBlockOfTypeFromCustomPost(
        $configurationPostOrID,
        AbstractBlock $block
    ): ?array {
        $blocks = $this->getBlocksOfTypeFromCustomPost($configurationPostOrID, $block);
        if (count($blocks) != 1) {
            return null;
        }
        return $blocks[0];
    }
}
