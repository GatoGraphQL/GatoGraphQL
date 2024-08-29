<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostCategoryTypeMutationAPI implements CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     */
    public function setCategoriesByID(
        string $taxonomyName,
        int|string $customPostID,
        array $categoryIDs,
        bool $append = false,
    ): void {
        \wp_set_post_terms((int)$customPostID, $categoryIDs, $taxonomyName, $append);
    }

    /**
     * @param string[] $categorySlugs
     */
    public function setCategoriesBySlug(
        string $taxonomyName,
        int|string $customPostID,
        array $categorySlugs,
        bool $append = false,
    ): void {
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
        \wp_set_object_terms((int)$customPostID, $categorySlugs, $taxonomyName, $append);
    }
}
