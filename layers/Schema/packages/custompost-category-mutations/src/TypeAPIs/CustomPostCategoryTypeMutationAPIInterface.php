<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\TypeAPIs;

interface CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param $categoryIDs array<string|int>
     */
    public function setCategories(int | string $postID, array $categoryIDs, bool $append = false): void;
}
