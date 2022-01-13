<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GD_LatestCounts_Utils
{
    public static function authorFilters($classes, array $module, array &$props)
    {

        // Allow URE to add Organization members
        return \PoP\Root\App::getHookManager()->applyFilters('latestcounts:author:classes', $classes, $module, $props);
    }

    public static function getAllcontentClasses(array $module, array &$props)
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
        return \PoP\Root\App::getHookManager()->applyFilters('latestcounts:allcontent:classes', $ret, $module, $props);
    }
}
