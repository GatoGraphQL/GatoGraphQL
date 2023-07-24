<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs;

interface CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categorySlugsOrIDs List of category slugs or IDs
     */
    public function setCategories(int|string $postID, array $categorySlugsOrIDs, bool $append = false): void;
}
