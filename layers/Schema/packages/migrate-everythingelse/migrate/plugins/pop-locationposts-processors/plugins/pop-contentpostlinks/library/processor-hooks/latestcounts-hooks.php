<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_LocationPosts_LatestCounts_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'latestcounts:locationposts:classes',
            array($this, 'getSectionClasses')
        );
    }

    public function getSectionClasses($classes)
    {
        $classes[] = 'post-'.POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS;
        return $classes;
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts_LatestCounts_Hooks();
