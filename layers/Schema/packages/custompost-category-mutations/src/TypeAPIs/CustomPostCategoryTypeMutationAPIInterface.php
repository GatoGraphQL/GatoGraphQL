<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\TypeAPIs;

interface CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param $categories string[]
     */
    public function setCategories(int | string $postID, array $categories, bool $append = false): void;
}
