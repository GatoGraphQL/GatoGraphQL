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

    public function getCategories($query, $options = []): array;
    public function getCategoryCount($query, $options = []): int;
    public function getCustomPostCategories($post_id, array $options = []): array;
    public function getCustomPostCategoryCount($post_id, $query, array $options = []): int;
    public function getCategoryName($cat_id);
    public function getCategoryParent($cat_id);
    public function getCategorySlug($catObjectOrID);
    public function getCategoryPath($category_id);
    public function hasCategory($cat_id, $post_id);
    public function getCategoryURL($category_id);


    public function getCategoryID($cat);
    public function getCategoryDescription($cat);
    public function getCategoryItemCount($cat);
}
