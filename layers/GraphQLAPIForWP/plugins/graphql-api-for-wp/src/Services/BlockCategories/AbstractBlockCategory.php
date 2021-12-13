<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use WP_Block_Editor_Context;
use WP_Post;

abstract class AbstractBlockCategory extends AbstractAutomaticallyInstantiatedService implements BlockCategoryInterface
{
    use BasicServiceTrait;

    final public function initialize(): void
    {
        /**
         * Starting from WP 5.8 the hook is a different one
         *
         * @see https://github.com/leoloso/PoP/issues/711
         */
        if (\is_wp_version_compatible('5.8')) {
            \add_filter(
                'block_categories_all',
                [$this, 'getBlockCategoriesViaBlockEditorContext'],
                10,
                2
            );
        } else {
            \add_filter(
                'block_categories',
                [$this, 'getBlockCategories'],
                10,
                2
            );
        }
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [];
    }

    /**
     * Block category's slug
     */
    abstract protected function getBlockCategorySlug(): string;

    /**
     * Block category's title
     */
    abstract protected function getBlockCategoryTitle(): string;

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array> $categories List of categories, each item is an array with props "slug" and "title"
     * @return array<array> List of categories, each item is an array with props "slug" and "title"
     */
    public function getBlockCategoriesViaBlockEditorContext(array $categories, WP_Block_Editor_Context $blockEditorContext): array
    {
        if ($blockEditorContext->post === null) {
            return $categories;
        }
        return $this->getBlockCategories(
            $categories,
            $blockEditorContext->post
        );
    }

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array> $categories List of categories, each item is an array with props "slug" and "title"
     * @return array<array> List of categories, each item is an array with props "slug" and "title"
     */
    public function getBlockCategories(array $categories, WP_Post $post): array
    {
        /**
         * If specified CPTs, register the category only for them
         */
        if (empty($this->getCustomPostTypes()) || in_array($post->post_type, $this->getCustomPostTypes())) {
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
