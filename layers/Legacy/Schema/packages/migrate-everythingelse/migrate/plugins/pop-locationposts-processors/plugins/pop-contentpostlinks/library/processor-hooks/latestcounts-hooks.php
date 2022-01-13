<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_LocationPosts_LatestCounts_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
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
