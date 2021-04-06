<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutationsWP\TypeAPIs;

use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeMutationAPI implements PostTagTypeMutationAPIInterface
{
    public function setTags(int | string $postID, array $tagIDs, bool $append = false): void
    {
        \wp_set_post_terms($postID, $tagIDs, 'post_tag', $append);
    }
}
