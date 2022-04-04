<?php

class PoP_ContentPostLinks_NoSearchCategoryPosts_LatestCounts_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'latestcounts:nosearchcategoryposts:classes',
            $this->getSectionClasses(...)
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
