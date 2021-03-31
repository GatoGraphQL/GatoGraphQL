<?php

namespace PoPSchema\Categories\WP;

use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class FunctionAPI extends \PoPSchema\Taxonomies\WP\FunctionAPI implements \PoPSchema\Categories\FunctionAPI
{
    use APITypeDataResolverTrait;

    public function __construct()
    {
        \PoPSchema\Categories\FunctionAPIFactory::setInstance($this);
    }

    protected function returnCategoryObjectsOrIDs($categories, $options = []): array
    {
        $return_type = $options['return-type'] ?? null;
        if ($return_type == ReturnTypes::IDS) {
            return array_map(
                function ($category) {
                    return $category->term_id;
                },
                $categories
            );
        }

        return $categories;
    }
    public function getCategories($query, $options = []): array
    {
        if (isset($query['hide-empty'])) {
            $query['hide_empty'] = $query['hide-empty'];
            unset($query['hide-empty']);
        }
        return $this->returnCategoryObjectsOrIDs((array) get_categories($query));
    }
    public function getCategoryCount($query, $options = []): int
    {
        $options['return-type'] = ReturnTypes::IDS;

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        return count($this->getCategories($query, $options));
    }

    public function getCustomPostCategories($post_id, array $options = []): array
    {
        $query = [];
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type == ReturnTypes::SLUGS) {
                $query['fields'] = 'slugs';
            }
        }
        return (array) wp_get_post_categories($post_id, $query);
    }
    public function getCustomPostCategoryCount($query, $options = []): int
    {
        $options['return-type'] = ReturnTypes::IDS;

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        return count($this->getCustomPostCategories($query, $options));
    }
    public function getCategoryName($cat_id)
    {
        return get_cat_name($cat_id);
    }
    public function getCategoryParent($cat_id)
    {
        $category = get_category($cat_id);
        return $category->parent;
    }
    public function getCategorySlug($cat_id)
    {
        $category = get_category($cat_id);
        return $category->slug;
    }

    public function getCategoryPath($category_id)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $taxonomy = 'category';

        // Convert it to int, otherwise it thinks it's a string and the method below fails
        $category_path = get_term_link((int) $category_id, $taxonomy);

        // Remove the initial part ("https://www.mesym.com/en/categories/")
        global $wp_rewrite;
        $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
        $termlink = str_replace("%$taxonomy%", '', $termlink);
        $termlink = $cmsengineapi->getHomeURL(user_trailingslashit($termlink, $taxonomy));

        return substr($category_path, strlen($termlink));
    }

    public function hasCategory($cat_id, $post_id)
    {
        return has_category($cat_id, $post_id);
    }
    public function getCategoryURL($cat)
    {
        return get_term_link($cat->term_id, 'category');
    }
}

/**
 * Initialize
 */
new FunctionAPI();
