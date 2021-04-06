<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTagTypeAPIInterface
{
    public function setTags(int | string $postID, array $tagIDs, bool $append = false): void;
}
