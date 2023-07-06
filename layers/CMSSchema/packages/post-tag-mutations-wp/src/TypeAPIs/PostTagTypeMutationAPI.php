<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutationsWP\TypeAPIs;

use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeMutationAPI implements PostTagTypeMutationAPIInterface
{
    /**
     * Watch out! $tags must be the array of tags as string, not their IDs
     * Passing the IDs will create a tag with that ID as the name!
     *
     * @param array<string|int> $tags List of tags by ID, slug, or a combination of them
     */
    public function setTags(int|string $postID, array $tags, bool $append = false): void
    {
        \wp_set_post_terms((int)$postID, $tags, 'post_tag', $append);
    }
}
