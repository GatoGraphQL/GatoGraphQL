<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\BlockCategories;

use WP_Post;

abstract class AbstractBlockCategory
{
    public function initialize(): void
    {
        \add_filter(
            'block_categories',
            [$this, 'getBlockCategories'],
            10,
            2
        );
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getPostTypes(): array
    {
        return [];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    abstract protected function getBlockCategorySlug(): string;

    /**
     * Block category's title
     *
     * @return string
     */
    abstract protected function getBlockCategoryTitle(): string;

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array> $categories List of categories, each item is an array with props "slug" and "title"
     * @param WP_Post $post
     * @return array<array> List of categories, each item is an array with props "slug" and "title"
     */
    public function getBlockCategories(array $categories, WP_Post $post): array
    {
        /**
         * If specified CPTs, register the category only for them
         */
        if (empty($this->getPostTypes()) || in_array($post->post_type, $this->getPostTypes())) {
            return [
                ...$categories,
                [
                    'slug' => $this->getBlockCategorySlug(),
                    'title' => $this->getBlockCategoryTitle(),
                ],
            ];
        }

        return $categories;
    }
}
