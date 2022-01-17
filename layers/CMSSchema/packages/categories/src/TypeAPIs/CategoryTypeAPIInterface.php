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

    public function getCategoryID(object $cat): string | int;
    public function getCategories(array $query, array $options = []): array;
    public function getCategoryCount(array $query, array $options = []): int;
    public function getCustomPostCategories(string | int $customPostID, array $query = [], array $options = []): array;
    public function getCustomPostCategoryCount(string | int $customPostID, array $query, array $options = []): int;
    public function getCategorySlug(string | int | object $catObjectOrID): string;
    public function getCategoryName(string | int | object $catObjectOrID): string;
    public function getCategoryParentID(string | int | object $catObjectOrID): string | int | null;
    /**
     * @return array<string|int>|null
     */
    public function getCategoryChildIDs(string | int | object $catObjectOrID): ?array;
    public function getCategoryURL(string | int | object $catObjectOrID): string;
    public function getCategoryURLPath(string | int | object $catObjectOrID): string;
    public function getCategoryDescription(string | int | object $catObjectOrID): string;
    public function getCategoryItemCount(string | int | object $catObjectOrID): int;
}
