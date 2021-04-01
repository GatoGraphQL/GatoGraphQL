<?php

namespace PoPSchema\Categories\WP;

use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\QueriedObject\TypeAPIs\TypeAPIUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

abstract class AbstractFunctionAPI extends \PoPSchema\Taxonomies\WP\FunctionAPI implements \PoPSchema\Categories\FunctionAPI
{
    use APITypeDataResolverTrait;

    public function __construct()
    {
        \PoPSchema\PostCategories\FunctionAPIFactory::setInstance($this);
    }

    /**
     * Implement this function by the actual service
     */
    abstract protected function getTaxonomyName(): string;
    /**
     * Implement this function by the actual service
     */
    abstract protected function getCategoryBaseOption(): string;

    public function getCategoryName($category_id)
    {
        $category = get_term($category_id, $this->getTaxonomyName());
        return $category->name;
    }
    public function getCategory($category_id)
    {
        return get_category($category_id, $this->getTaxonomyName());
    }
    public function getCategoryByName($category_name)
    {
        return get_term_by('name', $category_name, $this->getTaxonomyName());
    }
    public function getCustomPostCategories($post_id, array $query = [], array $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);

        return \wp_get_post_terms($post_id, $this->getTaxonomyName(), $query);
    }
    public function getCustomPostCategoryCount($post_id, array $query = [], array $options = []): int
    {
        // There is no direct way to calculate the total
        // (Documentation mentions to pass arg "count" => `true` to `wp_get_post_categories`,
        // but it doesn't work)
        // So execute a normal `wp_get_post_categories` retrieving all the IDs, and count them
        $options['return-type'] = ReturnTypes::IDS;
        $query = $this->convertCategoriesQuery($query, $options);

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Resolve and count
        $categories = \wp_get_post_terms($post_id, $this->getTaxonomyName(), $query);
        return count($categories);
    }
    public function getCategoryCount($query = [], $options = []): int
    {
        // There is no direct way to calculate the total
        // (Documentation mentions to pass arg "count" => `true` to `get_categories`,
        // but it doesn't work)
        // So execute a normal `get_categories` retrieving all the IDs, and count them
        $options['return-type'] = ReturnTypes::IDS;
        $query = $this->convertCategoriesQuery($query, $options);

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Resolve and count
        $categories = get_categories($query, ['taxonomy' => $this->getTaxonomyName()]);
        return count($categories);
    }
    public function getCategories($query, $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);
        return get_categories($query, ['taxonomy' => $this->getTaxonomyName()]);
    }

    public function convertCategoriesQuery($query, array $options = [])
    {
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type == ReturnTypes::NAMES) {
                $query['fields'] = 'names';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        if (isset($query['hide-empty'])) {
            $query['hide_empty'] = $query['hide-empty'];
            unset($query['hide-empty']);
        }

        // Convert the parameters
        if (isset($query['include'])) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            // Maybe restrict the limit, if higher than the max limit
            // Allow to not limit by max when querying from within the application
            $limit = (int) $query['limit'];
            if (!isset($options['skip-max-limit']) || !$options['skip-max-limit']) {
                $limit = TypeAPIUtils::getLimitOrMaxLimit(
                    $limit,
                    ComponentConfiguration::getCategoryListMaxLimit()
                );
            }

            // Assign the limit as the required attribute
            // To bring all results, get_categories needs "number => 0" instead of -1
            $query['number'] = ($limit == -1) ? 0 : $limit;
            unset($query['limit']);
        }
        if (isset($query['search'])) {
            // Same param name, so do nothing
        }
        if (isset($query['slugs'])) {
            $query['slug'] = $query['slugs'];
            unset($query['slugs']);
        }

        return HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:categories:query',
            $query,
            $options
        );
    }
    public function getCategoryURL($category_id)
    {
        return get_term_link($category_id, $this->getTaxonomyName());
    }
    public function getCategoryBase()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return $cmsService->getOption($this->getCategoryBaseOption());
    }

    public function setPostCategories($post_id, array $categories, bool $append = false)
    {
        return wp_set_post_terms($post_id, $categories, $this->getTaxonomyName(), $append);
    }

    // protected function returnCategoryObjectsOrIDs($categories, $options = []): array
    // {
    //     $return_type = $options['return-type'] ?? null;
    //     if ($return_type == ReturnTypes::IDS) {
    //         return array_map(
    //             function ($category) {
    //                 return $category->term_id;
    //             },
    //             $categories
    //         );
    //     }

    //     return $categories;
    // }
    // public function getCategories($query, $options = []): array
    // {
    //     if (isset($query['hide-empty'])) {
    //         $query['hide_empty'] = $query['hide-empty'];
    //         unset($query['hide-empty']);
    //     }
    //     return $this->returnCategoryObjectsOrIDs((array) get_categories($query));
    // }

    // public function getCategoryCount($query, $options = []): int
    // {
    //     $options['return-type'] = ReturnTypes::IDS;

    //     // All results, no offset
    //     $query['number'] = 0;
    //     unset($query['offset']);

    //     return count($this->getCategories($query, $options));
    // }

    // public function getCustomPostCategories($post_id, array $options = []): array
    // {
    //     $query = [];
    //     if ($return_type = $options['return-type'] ?? null) {
    //         if ($return_type == ReturnTypes::IDS) {
    //             $query['fields'] = 'ids';
    //         } elseif ($return_type == ReturnTypes::SLUGS) {
    //             $query['fields'] = 'slugs';
    //         }
    //     }
    //     return (array) wp_get_post_categories($post_id, $query);
    // }
    // public function getCustomPostCategoryCount($query, $options = []): int
    // {
    //     $options['return-type'] = ReturnTypes::IDS;

    //     // All results, no offset
    //     $query['number'] = 0;
    //     unset($query['offset']);

    //     return count($this->getCustomPostCategories($query, $options));
    // }
    // public function getCategoryName($cat_id)
    // {
    //     return get_cat_name($cat_id);
    // }
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
    // public function getCategoryURL($cat)
    // {
    //     return get_term_link($cat->term_id, 'category');
    // }
}

