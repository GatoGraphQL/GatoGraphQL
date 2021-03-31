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
