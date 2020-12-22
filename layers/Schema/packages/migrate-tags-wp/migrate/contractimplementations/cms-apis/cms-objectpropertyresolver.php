<?php
namespace PoPSchema\Tags\WP;

class ObjectPropertyResolver extends \PoPSchema\Tags\ObjectPropertyResolver_Base
{
    public function getTagName($tag)
    {
        return $tag->name;
    }
    public function getTagSlug($tag)
    {
        return $tag->slug;
    }
    public function getTagDescription($tag)
    {
        return $tag->description;
    }
    public function getTagParent($tag)
    {
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $tag->parent) {
            return $parent;
        }
        return null;
    }
    public function getTagCount($tag)
    {
        return $tag->count;
    }
    public function getTagID($tag)
    {
        return $tag->term_id;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
