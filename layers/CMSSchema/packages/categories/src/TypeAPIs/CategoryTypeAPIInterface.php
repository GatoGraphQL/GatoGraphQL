<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Category
     */
    public function isInstanceOfCategoryType(object $object): bool;

    public function getCategoryID(object $cat): string|int;
    public function getCategory(string|int $categoryID): ?object;
    public function categoryExists(int|string $id): bool;
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategories(array $query, array $options = []): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCategoryCount(array $query, array $options = []): int;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPostCategories(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCategoryCount(string|int|object $customPostObjectOrID, array $query, array $options = []): ?int;
    public function getCategorySlug(string|int|object $catObjectOrID): ?string;
    public function getCategorySlugPath(string|int|object $catObjectOrID): ?string;
    public function getCategoryName(string|int|object $catObjectOrID): ?string;
    public function getCategoryParentID(string|int|object $catObjectOrID): string|int|null;
    /**
     * @return array<string|int>|null
     */
    public function getCategoryChildIDs(string|int|object $catObjectOrID): ?array;
    public function getCategoryURL(string|int|object $catObjectOrID): ?string;
    public function getCategoryURLPath(string|int|object $catObjectOrID): ?string;
    public function getCategoryDescription(string|int|object $catObjectOrID): ?string;
    public function getCategoryItemCount(string|int|object $catObjectOrID): ?int;
}
