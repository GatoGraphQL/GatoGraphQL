<?php

class GD_LatestCounts_Utils
{
    public static function authorFilters($classes, array $componentVariation, array &$props)
    {

        // Allow URE to add Organization members
        return \PoP\Root\App::applyFilters('latestcounts:author:classes', $classes, $componentVariation, $props);
    }

    public static function getAllcontentClasses(array $componentVariation, array &$props)
    {
        $ret = array();

        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (defined('POP_TAXONOMIES_INITIALIZED') && PoP_Application_Taxonomy_ConfigurationUtils::hookAllcontentComponents()) {
            foreach (gdDataloadAllcontentCategories() as $cat) {
                $ret[] = 'post-'.$cat;
            }
        }
        else {
            // Use the post types as classes
            $ret = array_merge(
                $ret,
                $cmsapplicationpostsapi->getAllcontentPostTypes()
            );
        }

        // WordPress can hook in the terms
        return \PoP\Root\App::applyFilters('latestcounts:allcontent:classes', $ret, $componentVariation, $props);
    }
}
