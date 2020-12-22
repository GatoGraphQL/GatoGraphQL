<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class UREPoP_RoleProcessors_LatestCounts_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'latestcounts:author:classes',
            array($this, 'getClasses')
        );
    }

    public function getClasses($classes)
    {
        $vars = ApplicationState::getVars();
        $author = $vars['routing-state']['queried-object-id'];

        // Add all the members of the community, if the author is a community, and we're on the Community+Members page
        $vars = ApplicationState::getVars();
        if (gdUreIsCommunity($author) && $vars['source'] == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {
            if ($members = gdUreGetActivecontributingcontentcommunitymembers($author)) {
                foreach ($members as $member) {
                    $classes[] = 'author'.$member;
                }
            }
        }
        return $classes;
    }
}

/**
 * Initialization
 */
new UREPoP_RoleProcessors_LatestCounts_Hooks();
