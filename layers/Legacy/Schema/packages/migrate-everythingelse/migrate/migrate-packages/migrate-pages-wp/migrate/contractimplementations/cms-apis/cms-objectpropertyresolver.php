<?php
namespace PoPCMSSchema\Pages\WP;

class ObjectPropertyResolver extends \PoPCMSSchema\Pages\ObjectPropertyResolver_Base
{
    public function getPageModified($page)
    {
    	return $page->post_modified;
    }

    /**
     * Get the ID of the static page for the homepage
     * Returns an ID (int? string?) or null
     */
    public function getHomeStaticPageID(): string | int
    {
        if (get_option('show_on_front') !== 'page') {
            // Errors go in here
            return null;
        }

        // This is the expected operation
        $static_page_id = (int) get_option('page_on_front');
        return $static_page_id > 0 ? $static_page_id : null;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
