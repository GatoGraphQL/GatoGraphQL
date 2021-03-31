<?php
namespace PoPSchema\Categories;

interface FunctionAPI extends \PoPSchema\Taxonomies\FunctionAPI
{
    public function getCategories($query, $options = []): array;
    public function getCategoryCount($query, $options = []): int;
    public function getCustomPostCategories($post_id, array $options = []): array;
    public function getCategoryName($cat_id);
    public function getCategoryParent($cat_id);
    public function getCategorySlug($cat_id);
    public function getCategoryPath($category_id);
    public function hasCategory($cat_id, $post_id);
}
