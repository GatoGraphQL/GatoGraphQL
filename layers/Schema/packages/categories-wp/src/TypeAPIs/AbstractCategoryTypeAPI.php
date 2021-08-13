<?php

declare(strict_types=1);

namespace PoPSchema\CategoriesWP\TypeAPIs;

use PoP\ComponentModel\TypeAPIs\InjectedFilterDataloadingModuleTypeAPITrait;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use WP_Error;
use WP_Taxonomy;
use WP_Term;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends TaxonomyTypeAPI implements CategoryTypeAPIInterface
{
    use InjectedFilterDataloadingModuleTypeAPITrait;

    public const HOOK_QUERY = __CLASS__ . ':query';

    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected CMSHelperServiceInterface $cmsHelperService,
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService,
    ) {
    }

    /**
     * Indicates if the passed object is of type Category
     */
    public function isInstanceOfCategoryType(object $object): bool
    {
        return ($object instanceof WP_Taxonomy) && $object->hierarchical == true;
    }

    public function getCategoryID(object $cat): string | int
    {
        return $cat->term_id;
    }

    abstract protected function getCategoryBaseOption(): string;

    abstract protected function getCategoryTaxonomyName(): string;

    public function getCategory($category_id)
    {
        return get_category($category_id, $this->getCategoryTaxonomyName());
    }
    public function getCategoryByName($category_name)
    {
        return get_term_by('name', $category_name, $this->getCategoryTaxonomyName());
    }
    public function getCustomPostCategories(string | int $customPostID, array $query = [], array $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);

        return \wp_get_post_terms($customPostID, $this->getCategoryTaxonomyName(), $query);
    }
    public function getCustomPostCategoryCount(string | int $customPostID, array $query = [], array $options = []): int
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
        $categories = \wp_get_post_terms($customPostID, $this->getCategoryTaxonomyName(), $query);
        return count($categories);
    }
    public function getCategoryCount(array $query = [], array $options = []): int
    {
        $query = $this->convertCategoriesQuery($query, $options);

        // Indicate to return the count
        $query['count'] = true;
        $query['fields'] = 'count';

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Execute query and return count
        /** @var int[] */
        $count = \get_categories($query);
        // For some reason, the count is returned as an array of 1 element!
        if (is_array($count) && count($count) === 1 && is_int($count[0])) {
            return (int) $count[0];
        }
        // An error happened
        return -1;
    }
    public function getCategories(array $query, array $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);
        return get_categories($query);
    }

    public function convertCategoriesQuery(array $query, array $options = []): array
    {
        $query['taxonomy'] = $this->getCategoryTaxonomyName();

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
        } else {
            // By default: do not hide empty categories
            $query['hide_empty'] = false;
        }

        // Convert the parameters
        if (isset($query['include'])) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['exclude'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
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
                $limit = $this->queriedObjectHelperService->getLimitOrMaxLimit(
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
        if (isset($query['parent-id'])) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }

        return $this->hooksAPI->applyFilters(
            TaxonomyTypeAPI::HOOK_QUERY,
            $this->hooksAPI->applyFilters(
                self::HOOK_QUERY,
                $query,
                $options
            ),
            $query,
            $options
        );
    }

    public function getCategoryURL(string | int | object $catObjectOrID): string
    {
        return \get_term_link($catObjectOrID, $this->getCategoryTaxonomyName());
    }

    public function getCategoryURLPath(string | int | object $catObjectOrID): string
    {
        /** @var string */
        return $this->cmsHelperService->getLocalURLPath($this->getCategoryURL($catObjectOrID));
    }

    public function getCategoryBase()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return $cmsService->getOption($this->getCategoryBaseOption());
    }

    public function setPostCategories($post_id, array $categories, bool $append = false)
    {
        return wp_set_post_terms($post_id, $categories, $this->getCategoryTaxonomyName(), $append);
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

    protected function getCategoryFromObjectOrID(string | int | object $catObjectOrID): ?WP_Term
    {
        if (is_object($catObjectOrID)) {
            return $catObjectOrID;
        }
        $catObject = \get_term($catObjectOrID, $this->getCategoryTaxonomyName());
        if ($catObject === null || $catObject instanceof WP_Error) {
            return null;
        }
        return $catObject;
    }

    public function getCategorySlug(string | int | object $catObjectOrID): string
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        return $category->slug;
    }

    public function getCategoryName(string | int | object $catObjectOrID): string
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        return $category->name;
    }

    public function getCategoryParentID(string | int | object $catObjectOrID): string | int | null
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $category->parent) {
            return $parent;
        }
        return null;
    }

    /**
     * @return array<string|int>|null
     */
    public function getCategoryChildIDs(string | int | object $catObjectOrID): ?array
    {
        $categoryID = is_object($catObjectOrID) ? $this->getCategoryID($catObjectOrID) : $catObjectOrID;
        $childrenIDs = \get_term_children($categoryID, $this->getCategoryTaxonomyName());
        if ($childrenIDs instanceof WP_Error) {
            return null;
        }
        return $childrenIDs;
    }

    public function getCategoryDescription(string | int | object $catObjectOrID): string
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        return $category->description;
    }
    public function getCategoryItemCount(string | int | object $catObjectOrID): int
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        return $category->count;
    }
}
