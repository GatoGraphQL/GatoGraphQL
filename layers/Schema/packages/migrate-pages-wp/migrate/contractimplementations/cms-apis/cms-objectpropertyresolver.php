<?php
namespace PoPSchema\Pages\WP;

class ObjectPropertyResolver extends \PoPSchema\Pages\ObjectPropertyResolver_Base
{
    public function getPageModified($page)
    {
    	return $page->post_modified;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
