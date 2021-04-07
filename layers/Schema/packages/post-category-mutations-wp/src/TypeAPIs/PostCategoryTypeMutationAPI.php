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
     * Watch out! $categories must be the array of categories as string, not their IDs
     * Passing the IDs will create a category with that ID as the name!
     *
     * @param $categories string[]
     */
    public function setCategories(int | string $postID, array $categories, bool $append = false): void
    {
        \wp_set_post_terms($postID, $categories, 'post_category', $append);
    }
}
