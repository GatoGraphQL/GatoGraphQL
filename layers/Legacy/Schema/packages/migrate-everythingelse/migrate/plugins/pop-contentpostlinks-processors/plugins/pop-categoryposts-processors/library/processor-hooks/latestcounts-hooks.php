<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ContentPostLinks_CategoryPosts_LatestCounts_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'latestcounts:categoryposts:classes',
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
new PoP_ContentPostLinks_CategoryPosts_LatestCounts_Hooks();
