<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use WP_Post;
use WP_Term;

use function get_categories;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends AbstractTaxonomyTypeAPI implements CategoryTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    private ?CMSServiceInterface $cmsService = null;

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
        return $this->isInstanceOfTaxonomyType($object);
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
        return $this->getTaxonomyTerm($categoryID);
    }

    public function categoryExists(int|string $id): bool
    {
        return $this->getCategory($id) !== null;
    }

    abstract protected function getCategoryBaseOption(): string;

    protected function getTaxonomyName(): string
    {
        return $this->getCategoryTaxonomyName();
    }

    abstract protected function getCategoryTaxonomyName(): string;

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCategories(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array
    {
        /** @var string|int|WP_Post $customPostObjectOrID */
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
        return get_categories($query);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery(array $query, array $options = []): array
    {
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
    public function convertCategoriesQuery(array $query, array $options = []): array
    {
        return $this->convertTaxonomyTermsQuery($query, $options);
    }

    public function getCategoryURL(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURL($catObjectOrID);
    }

    public function getCategoryURLPath(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURLPath($catObjectOrID);
    }

    public function getCategoryBase(): string
    {
        return $this->getCMSService()->getOption($this->getCategoryBaseOption());
    }

    protected function getCategoryFromObjectOrID(string|int|object $catObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID($catObjectOrID);
    }

    public function getCategorySlug(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermSlug($catObjectOrID);
    }

    public function getCategoryName(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermName($catObjectOrID);
    }

    public function getCategoryParentID(string|int|object $catObjectOrID): string|int|null
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermParentID($catObjectOrID);
    }

    /**
     * @return array<string|int>|null
     */
    public function getCategoryChildIDs(string|int|object $catObjectOrID): ?array
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermChildIDs($catObjectOrID);
    }

    public function getCategoryDescription(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermDescription($catObjectOrID);
    }

    public function getCategoryItemCount(string|int|object $catObjectOrID): ?int
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermItemCount($catObjectOrID);
    }
}
