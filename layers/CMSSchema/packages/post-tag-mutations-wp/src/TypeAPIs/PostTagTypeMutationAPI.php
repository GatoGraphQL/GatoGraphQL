<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutationsWP\TypeAPIs;

use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeMutationAPI implements PostTagTypeMutationAPIInterface
{
    /**
     * Watch out! $tags must be the array of tags as string, not their IDs
     * Passing the IDs will create a tag with that ID as the name!
     *
     * @param string[] $tags
     */
    public function setTags(int | string $postID, array $tags, bool $append = false): void
    {
        \wp_set_post_terms($postID, $tags, 'post_tag', $append);
    }
}
