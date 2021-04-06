<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutationsWP\TypeAPIs;

use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeAPI implements PostTagTypeAPIInterface
{
    public function setTags(int | string $postID, array $tagIDs, bool $append = false): void
    {
        \wp_set_post_terms($postID, $tagIDs, 'post_tag', $append);
    }
}
