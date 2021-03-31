<?php

namespace PoPSchema\Categories\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\QueriedObject\TypeAPIs\TypeAPIUtils;
use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

abstract class AbstractFunctionAPI extends \PoPSchema\Taxonomies\WP\FunctionAPI implements \PoPSchema\Categories\FunctionAPI
{
    use APITypeDataResolverTrait;

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
    public function getCategoryCount(array $query = [], array $options = []): int
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
    public function getCategories($query, array $options = []): array
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
    public function getCategoryLink($category_id)
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
}

