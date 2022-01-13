<?php

class PoP_LocationPosts_LatestCounts_ClassesHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'latestcounts:allcontent:classes',
            array($this, 'getAllcontentClasses')
        );
    }

    public function getAllcontentClasses($classes)
    {
        if (defined('POP_TAXONOMIES_INITIALIZED') && PoP_Application_Taxonomy_ConfigurationUtils::hookAllcontentComponents()) {
            if (defined('POP_LOCATIONPOSTS_CAT_ALL') && POP_LOCATIONPOSTS_CAT_ALL) {
                $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
                if (in_array(POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
                    $classes[] = POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST.'-'.POP_LOCATIONPOSTS_CAT_ALL;
                }
            }
        }

        return $classes;
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts_LatestCounts_ClassesHooks();
