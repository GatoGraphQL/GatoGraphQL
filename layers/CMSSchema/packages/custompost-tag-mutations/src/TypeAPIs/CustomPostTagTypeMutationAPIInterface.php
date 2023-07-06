<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeAPIs;

interface CustomPostTagTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $tags List of tags by ID, slug, or a combination of them
     */
    public function setTags(int|string $postID, array $tags, bool $append = false): void;
}
