<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ContentPostLinks_NoSearchCategoryPosts_LatestCounts_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'latestcounts:nosearchcategoryposts:classes',
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
new PoP_ContentPostLinks_NoSearchCategoryPosts_LatestCounts_Hooks();
