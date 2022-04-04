<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use WP_Error;
use WP_Taxonomy;
use WP_Term;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends TaxonomyTypeAPI implements CategoryTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    private ?CMSHelperServiceInterface $cmsHelperService = null;
    private ?CMSServiceInterface $cmsService = null;

    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }
    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
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
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
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
        if (is_array($count) && count($count) === 1 && is_numeric($count[0])) {
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
        $query = $this->convertTaxonomiesQuery($query, $options);

        // Convert the parameters
        $query['taxonomy'] = $this->getCategoryTaxonomyName();

        if (isset($query['parent-id'])) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
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
        return $this->getCMSHelperService()->getLocalURLPath($this->getCategoryURL($catObjectOrID));
    }

    public function getCategoryBase()
    {
        return $this->getCMSService()->getOption($this->getCategoryBaseOption());
    }

    public function setPostCategories($post_id, array $categories, bool $append = false)
    {
        return wp_set_post_terms($post_id, $categories, $this->getCategoryTaxonomyName(), $append);
    }

    // protected function returnCategoryObjectsOrIDs($categories, $options = []): array
    // {
    //     $return_type = $options[QueryOptions::RETURN_TYPE] ?? null;
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
    //     $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;

    //     // All results, no offset
    //     $query['number'] = 0;
    //     unset($query['offset']);

    //     return count($this->getCategories($query, $options));
    // }

    // public function getCustomPostCategories($post_id, array $options = []): array
    // {
    //     $query = [];
    //     if ($return_type = $options[QueryOptions::RETURN_TYPE] ?? null) {
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
    //     $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;

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
