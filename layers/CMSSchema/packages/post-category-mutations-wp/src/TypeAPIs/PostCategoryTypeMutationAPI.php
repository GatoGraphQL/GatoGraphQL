<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutationsWP\TypeAPIs;

use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeMutationAPI implements PostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categorySlugsOrIDs List of category slugs or IDs
     */
    public function setCategories(int|string $postID, array $categorySlugsOrIDs, bool $append = false): void
    {
        \wp_set_post_terms((int)$postID, $categorySlugsOrIDs, 'category', $append);
    }
}
