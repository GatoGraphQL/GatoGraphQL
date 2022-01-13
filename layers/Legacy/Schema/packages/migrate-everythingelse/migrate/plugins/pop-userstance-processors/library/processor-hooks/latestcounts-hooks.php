<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserStance_LatestCounts_ClassesHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'latestcounts:allcontent:classes',
            array($this, 'getAllcontentClasses')
        );
    }

    public function getAllcontentClasses($classes)
    {
        if (defined('POP_TAXONOMIES_INITIALIZED') && PoP_Application_Taxonomy_ConfigurationUtils::hookAllcontentComponents()) {
            $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
            if (in_array(POP_USERSTANCE_POSTTYPE_USERSTANCE, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
                $classes[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_PRO;
                $classes[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_AGAINST;
                $classes[] = POP_USERSTANCE_POSTTYPE_USERSTANCE.'-'.POP_USERSTANCE_TERM_STANCE_NEUTRAL;
            }
        }

        return $classes;
    }
}

/**
 * Initialization
 */
new PoP_UserStance_LatestCounts_ClassesHooks();
