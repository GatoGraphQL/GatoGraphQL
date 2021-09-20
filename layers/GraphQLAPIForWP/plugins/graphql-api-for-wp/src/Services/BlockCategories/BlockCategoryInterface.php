<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use WP_Block_Editor_Context;
use WP_Post;

interface BlockCategoryInterface
{
    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array;

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array> $categories List of categories, each item is an array with props "slug" and "title"
     * @return array<array> List of categories, each item is an array with props "slug" and "title"
     */
    public function getBlockCategoriesViaBlockEditorContext(array $categories, WP_Block_Editor_Context $blockEditorContext): array;

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array> $categories List of categories, each item is an array with props "slug" and "title"
     * @return array<array> List of categories, each item is an array with props "slug" and "title"
     */
    public function getBlockCategories(array $categories, WP_Post $post): array;
}
