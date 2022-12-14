<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use WP_Error;
use WP_Taxonomy;
use WP_Term;

use function wp_get_post_terms;
use function get_categories;
use function get_term_link;
use function get_term_children;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends AbstractTaxonomyTypeAPI implements CategoryTypeAPIInterface
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
        /** @var CMSHelperServiceInterface */
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }
    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        /** @var CMSServiceInterface */
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }

    /**
     * Indicates if the passed object is of type Category
     */
    public function isInstanceOfCategoryType(object $object): bool
    {
        return ($object instanceof WP_Taxonomy) && $object->hierarchical;
    }

    public function getCategoryID(object $cat): string|int
    {
        /** @var WP_Term $cat */
        return $cat->term_id;
    }

    abstract protected function getCategoryBaseOption(): string;

    abstract protected function getCategoryTaxonomyName(): string;

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCategories(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        $query = $this->convertCategoriesQuery($query, $options);
        $categories =  wp_get_post_terms(
            (int)$customPostID,
            $this->getCategoryTaxonomyName(),
            $query,
        );
        if ($categories instanceof WP_Error) {
            return [];
        }
        /** @var array<string|int>|object[] $categories */
        return $categories;
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCategoryCount(string|int|object $customPostObjectOrID, array $query = [], array $options = []): ?int
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);

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
        $categories = wp_get_post_terms(
            (int)$customPostID,
            $this->getCategoryTaxonomyName(),
            $query,
        );
        if ($categories instanceof WP_Error) {
            return null;
        }
        /** @var int[] $categories */
        return count($categories);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
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
        $count = get_categories($query);

        // For some reason, the count is returned as an array of 1 element!
        if (is_array($count) && count($count) === 1 && is_numeric($count[0])) {
            return (int) $count[0];
        }
        // An error happened
        return -1;
    }
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategories(array $query, array $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);
        return get_categories($query);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
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

    public function getCategoryURL(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        $termLink = get_term_link($catObjectOrID, $this->getCategoryTaxonomyName());
        if ($termLink instanceof WP_Error) {
            return null;
        }
        return $termLink;
    }

    public function getCategoryURLPath(string|int|object $catObjectOrID): ?string
    {
        $categoryURL = $this->getCategoryURL($catObjectOrID);
        if ($categoryURL === null) {
            return null;
        }
        /** @var string */
        return $this->getCMSHelperService()->getLocalURLPath($categoryURL);
    }

    public function getCategoryBase(): string
    {
        return $this->getCMSService()->getOption($this->getCategoryBaseOption());
    }

    // protected function returnCategoryObjectsOrIDs($categories, $options = []): array
    // {
    //     $return_type = $options[QueryOptions::RETURN_TYPE] ?? null;
    //     if ($return_type === ReturnTypes::IDS) {
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
    //         if ($return_type === ReturnTypes::IDS) {
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

    protected function getCategoryFromObjectOrID(string|int|object $catObjectOrID): ?WP_Term
    {
        if (is_object($catObjectOrID)) {
            /** @var WP_Term */
            return $catObjectOrID;
        }
        $catObject = $this->getTerm($catObjectOrID, $this->getCategoryTaxonomyName());
        if ($catObject === null) {
            return null;
        }
        return $catObject;
    }

    public function getCategorySlug(string|int|object $catObjectOrID): ?string
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        if ($category === null) {
            return null;
        }
        return $category->slug;
    }

    public function getCategoryName(string|int|object $catObjectOrID): ?string
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        if ($category === null) {
            return null;
        }
        return $category->name;
    }

    public function getCategoryParentID(string|int|object $catObjectOrID): string|int|null
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        if ($category === null) {
            return null;
        }
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $category->parent) {
            return $parent;
        }
        return null;
    }

    /**
     * @return array<string|int>|null
     */
    public function getCategoryChildIDs(string|int|object $catObjectOrID): ?array
    {
        $categoryID = is_object($catObjectOrID) ? $this->getCategoryID($catObjectOrID) : $catObjectOrID;
        $childrenIDs = get_term_children((int)$categoryID, $this->getCategoryTaxonomyName());
        if ($childrenIDs instanceof WP_Error) {
            return null;
        }
        return $childrenIDs;
    }

    public function getCategoryDescription(string|int|object $catObjectOrID): ?string
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        if ($category === null) {
            return null;
        }
        return $category->description;
    }
    public function getCategoryItemCount(string|int|object $catObjectOrID): ?int
    {
        $category = $this->getCategoryFromObjectOrID($catObjectOrID);
        if ($category === null) {
            return null;
        }
        return $category->count;
    }
}
