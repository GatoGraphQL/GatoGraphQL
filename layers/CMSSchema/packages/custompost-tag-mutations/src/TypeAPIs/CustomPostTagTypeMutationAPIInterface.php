<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeAPIs;

interface CustomPostTagTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $tagIDs
     */
    public function setTagsByID(
        string $taxonomyName,
        int|string $customPostID,
        array $tagIDs,
        bool $append = false,
    ): void;
    
    /**
     * @param array<string|int> $tagSlugs
     */
    public function setTagsBySlug(
        string $taxonomyName,
        int|string $customPostID,
        array $tagSlugs,
        bool $append = false,
    ): void;
}
