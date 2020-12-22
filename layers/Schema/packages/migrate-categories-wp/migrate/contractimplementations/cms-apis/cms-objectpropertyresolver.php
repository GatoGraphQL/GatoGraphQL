<?php
namespace PoPSchema\Categories\WP;

class ObjectPropertyResolver extends \PoPSchema\Categories\ObjectPropertyResolver_Base
{
    public function getCategoryID($cat)
    {
        return $cat->term_id;
    }
    public function getCategorySlug($cat)
    {
        return $cat->slug;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
