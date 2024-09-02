<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\CategoryListTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use PoP\Root\App;
use WP_Post;
use WP_Term;

use function get_categories;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends AbstractTaxonomyTypeAPI implements CategoryTypeAPIInterface, CategoryListTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Indicates if the passed object is of type Category
     */
    public function isInstanceOfCategoryType(object $object): bool
    {
        if (!$this->isInstanceOfTaxonomyTermType($object)) {
            return false;
        }
        /** @var WP_Term $object */
        return $this->isCategoryTaxonomy($object);
    }

    protected function isHierarchical(): bool
    {
        return true;
    }

    public function getCategoryID(object $cat): string|int
    {
        /** @var WP_Term $cat */
        return $this->getTaxonomyTermID($cat);
    }

    public function getCategory(string|int $categoryID): ?object
    {
        $category = $this->getTaxonomyTerm(
            $categoryID,
            $this->getCategoryTaxonomyName(),
        );
        if ($category === null) {
            return null;
        }
        /** @var WP_Term $category */
        if (!$this->isCategoryTaxonomy($category)) {
            return null;
        }
        return $category;
    }

    public function categoryExists(int|string $id): bool
    {
        return $this->getCategory($id) !== null;
    }

    abstract protected function getCategoryTaxonomyName(): string;
    
    /**
     * @return string[]
     */
    abstract protected function getCategoryTaxonomyNames(): array;

    protected function isCategoryTaxonomy(WP_Term $taxonomyTerm): bool
    {
        return in_array($taxonomyTerm->taxonomy, $this->getCategoryTaxonomyNames());
    }

    /**
     * @param string|int|WP_Post $customPostObjectOrID
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPostCategories(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getCategoryTaxonomyNames();
        }

        /** @var array<string|int>|object[] */
        return $this->getCustomPostTaxonomyTerms(
            $customPostObjectOrID,
            $query,
            $options,
        );
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCategoryCount(string|int|object $customPostObjectOrID, array $query = [], array $options = []): ?int
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getCategoryTaxonomyNames();
        }

        /** @var string|int|WP_Post $customPostObjectOrID */
        return $this->getCustomPostTaxonomyTermCount(
            $customPostObjectOrID,
            $query,
            $options,
        );
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategoryCount(array $query = [], array $options = []): int
    {
        /** @var int */
        return $this->getTaxonomyCount($query, $options);
    }
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategories(array $query, array $options = []): array
    {
        $query = $this->convertCategoriesQuery($query, $options);

        // If passing an empty array to `filter.ids`, return no results
        if ($this->isFilteringByEmptyArray($query)) {
            return [];
        }

        return get_categories($query);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery(array $query, array $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getCategoryTaxonomyNames();
        }
        $query = parent::convertTaxonomyTermsQuery($query, $options);
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    final public function convertCategoriesQuery(array $query, array $options = []): array
    {
        return $this->convertTaxonomyTermsQuery($query, $options);
    }

    public function getCategoryURL(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURL(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    public function getCategoryURLPath(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURLPath(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    protected function getCategoryFromObjectOrID(string|int|object $catObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    protected function getTaxonomyTermFromObjectOrID(
        string|int|WP_Term $taxonomyTermObjectOrID,
        string $taxonomy = '',
    ): ?WP_Term {
        $taxonomyTerm = parent::getTaxonomyTermFromObjectOrID(
            $taxonomyTermObjectOrID,
            $taxonomy,
        );
        if ($taxonomyTerm === null) {
            return $taxonomyTerm;
        }
        /** @var WP_Term $taxonomyTerm */
        return $this->isCategoryTaxonomy($taxonomyTerm) ? $taxonomyTerm : null;
    }

    public function getCategorySlug(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermSlug(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    public function getCategorySlugPath(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermSlugPath(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    public function getCategoryName(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermName(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    public function getCategoryParentID(string|int|object $catObjectOrID): string|int|null
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermParentID(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    /**
     * @return array<string|int>|null
     */
    public function getCategoryChildIDs(string|int|object $catObjectOrID): ?array
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermChildIDs($this->getCategoryTaxonomyName(), $catObjectOrID);
    }

    public function getCategoryDescription(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermDescription(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }

    public function getCategoryItemCount(string|int|object $catObjectOrID): ?int
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermItemCount(
            $catObjectOrID,
            $this->getCategoryTaxonomyName(),
        );
    }
}
