<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_TrendingTags_SidebarHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_SidebarMultiplesBase:modules',
            array($this, 'getSidebarSubmodules'),
            0,
            4
        );
    }

    public function getSidebarSubmodules($modules, $screengroup, $screen, array $module)
    {

        // Add the Trending Tags to all Groups in the Sideinfo
        // Allow GetPoP to remove it
        $includeScreengroups = HooksAPIFacade::getInstance()->applyFilters(
            'PoPTheme_Wassup_SidebarHooks:sidebar_modules:includeScreengroups',
            array(
                POP_SCREENGROUP_CONTENTREAD,
            )
        );
        $exclude_screens = array(
            POP_SCREEN_TAGS,
        );
        if (in_array($screengroup, $includeScreengroups) && !in_array($screen, $exclude_screens)) {
            $modules[] = [PoP_TrendingTags_Module_Processor_SectionBlocks::class, PoP_TrendingTags_Module_Processor_SectionBlocks::MODULE_BLOCK_TRENDINGTAGS_SCROLL_LIST];
        }

        return $modules;
    }
}

/**
 * Initialization
 */
new PoP_TrendingTags_SidebarHooks();
