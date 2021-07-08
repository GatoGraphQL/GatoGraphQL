<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutationsWP\TypeAPIs;

use PoPSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeMutationAPI implements PostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     */
    public function setCategories(int | string $postID, array $categoryIDs, bool $append = false): void
    {
        \wp_set_post_terms($postID, $categoryIDs, 'category', $append);
    }
}
