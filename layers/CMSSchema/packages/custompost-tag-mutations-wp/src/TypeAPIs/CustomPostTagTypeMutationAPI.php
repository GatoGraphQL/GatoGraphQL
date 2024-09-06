<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;

use function wp_set_post_terms;

class CustomPostTagTypeMutationAPI implements CustomPostTagTypeMutationAPIInterface
{
    /**
     * Watch out! $tags must be the array of tags as string, not their IDs
     * Passing the IDs will create a tag with that ID as the name!
     *
     * @param array<string|int> $tagIDs
     */
    public function setTagsByID(
        string $taxonomyName,
        int|string $customPostID,
        array $tagIDs,
        bool $append = false,
    ): void {
        wp_set_post_terms((int)$customPostID, $tagIDs, $taxonomyName, $append);
    }

    /**
     * @param array<string|int> $tagSlugs
     */
    public function setTagsBySlug(
        string $taxonomyName,
        int|string $customPostID,
        array $tagSlugs,
        bool $append = false,
    ): void {
        /**
         * To use this method, make sure that tags with the provided slugs exist!
         * Otherwise, it will create them as terms.
         */
        \wp_set_object_terms((int)$customPostID, $tagSlugs, $taxonomyName, $append);
    }
}
