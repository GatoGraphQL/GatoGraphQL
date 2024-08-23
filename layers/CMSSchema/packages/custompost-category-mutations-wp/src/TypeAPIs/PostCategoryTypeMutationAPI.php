<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutationsWP\TypeAPIs;

use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeMutationAPI implements PostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     */
    public function setCategoriesByID(int|string $postID, array $categoryIDs, bool $append = false): void
    {
        \wp_set_post_terms((int)$postID, $categoryIDs, 'category', $append);
    }

    /**
     * @param string[] $categorySlugs
     */
    public function setCategoriesBySlug(int|string $postID, array $categorySlugs, bool $append = false): void
    {
        /**
         * Watch out! Can't use `wp_set_post_terms` because it only accepts
         * category IDs and not slugs:
         *
         *   > Hierarchical taxonomies must always pass IDs rather than names so that
         *   > children with the same names but different parents aren't confused.
         *
         * @see wp-includes/post.php
         *
         * To use this method, make sure that categories with the provided slugs exist!
         * Otherwise, it will create them as terms.
         */
        \wp_set_object_terms((int)$postID, $categorySlugs, 'category', $append);
    }
}
