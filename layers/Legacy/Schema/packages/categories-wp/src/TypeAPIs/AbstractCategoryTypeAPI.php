<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTermTypeAPI;

use function wp_set_post_terms;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends TaxonomyTermTypeAPI implements CategoryTypeAPIInterface
{
    public function hasCategory($cat_id, $post_id): bool
    {
        return has_category($cat_id, $post_id);
    }

    public function getCategoryPath($category_id): string
    {
        $taxonomy = 'category';

        // Convert it to int, otherwise it thinks it's a string and the method below fails
        $category_path = get_term_link((int) $category_id, $taxonomy);

        // Remove the initial part ("https://www.mesym.com/en/categories/")
        global $wp_rewrite;
        $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
        $termlink = str_replace("%$taxonomy%", '', $termlink);
        $termlink = home_url(user_trailingslashit($termlink, $taxonomy));

        return substr($category_path, strlen($termlink));
    }

    /**
     * @param mixed[] $categories
     */
    public function setPostCategories(string|int $post_id, array $categories, bool $append = false): void
    {
        wp_set_post_terms($post_id, $categories, $this->getCategoryTaxonomyName(), $append);
    }
}
