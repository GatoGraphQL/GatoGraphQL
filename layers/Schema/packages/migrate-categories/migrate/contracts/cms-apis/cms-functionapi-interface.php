<?php
namespace PoPSchema\Categories;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

interface FunctionAPI extends TaxonomyTypeAPIInterface
{
    public function getCategories($query, $options = []): array;
    public function getCategoryCount($query, $options = []): int;
    public function getCustomPostCategories($post_id, array $options = []): array;
    public function getCustomPostCategoryCount($post_id, $query, array $options = []): int;
    public function getCategoryName($cat_id);
    public function getCategoryParent($cat_id);
    public function getCategorySlug($cat_id);
    public function getCategoryPath($category_id);
    public function hasCategory($cat_id, $post_id);
    public function getCategoryURL($category_id);
}
