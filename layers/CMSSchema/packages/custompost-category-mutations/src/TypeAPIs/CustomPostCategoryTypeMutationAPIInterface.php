<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs;

interface CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     */
    public function setCategoriesByID(int|string $postID, array $categoryIDs, bool $append = false): void;
    /**
     * @param string[] $categorySlugs
     */
    public function setCategoriesBySlug(int|string $postID, array $categorySlugs, bool $append = false): void;
}
