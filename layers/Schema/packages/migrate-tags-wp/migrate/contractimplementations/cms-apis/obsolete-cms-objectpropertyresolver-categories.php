<?php
namespace PoPSchema\Categories\WP;

class ObjectPropertyResolver extends \PoPSchema\Categories\ObjectPropertyResolver_Base
{
    public function getCategoryName($category)
    {
        return $category->name;
    }
    public function getCategorySlug($category)
    {
        return $category->slug;
    }
    public function getCategoryDescription($category)
    {
        return $category->description;
    }
    public function getCategoryParent($category)
    {
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $category->parent) {
            return $parent;
        }
        return null;
    }
    public function getCategoryCount($category)
    {
        return $category->count;
    }
    public function getCategoryID($category)
    {
        return $category->term_id;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
