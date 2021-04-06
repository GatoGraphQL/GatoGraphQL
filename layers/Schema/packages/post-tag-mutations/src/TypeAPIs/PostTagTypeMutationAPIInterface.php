<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\TypeAPIs;

interface PostTagTypeMutationAPIInterface
{
    /**
     * @param $tags string[]
     */
    public function setTags(int | string $postID, array $tags, bool $append = false): void;
}
