<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

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
    public function getCustomPostCategories(string | int $customPostID, array $options = []): array;
    public function getCustomPostCategoryCount(string | int $customPostID, array $query, array $options = []): int;

    public function getCategorySlug(string | int | object $catObjectOrID): string;
    public function getCategoryName(string | int | object $catObjectOrID): string;
    public function getCategoryParentID(string | int | object $catObjectOrID): string | int;

    public function hasCategory($catObjectOrID, $post_id);

    public function getCategoryPath($category_id);
    public function getCategoryURL($category_id);


    public function getCategoryDescription($cat);
    public function getCategoryItemCount($cat);
}
