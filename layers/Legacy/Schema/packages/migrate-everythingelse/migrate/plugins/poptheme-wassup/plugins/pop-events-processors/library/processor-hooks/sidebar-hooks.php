<?php

class PoPTheme_Wassup_EM_SidebarHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_SidebarMultiplesBase:modules',
            array($this, 'getSidebarSubmodules'),
            0,
            4
        );
    }

    public function getSidebarSubmodules($modules, $screengroup, $screen, array $module)
    {

        // Add the Events Calendar to all Groups in the Sideinfo
        $includeScreengroups = \PoP\Root\App::getHookManager()->applyFilters(
            'PoPTheme_Wassup_EM_SidebarHooks:sidebar_modules:includeScreengroups',
            array(
                POP_SCREENGROUP_CONTENTREAD,
            )
        );
        if (in_array($screengroup, $includeScreengroups)/* && !in_array($screen, $exclude_screens)*/) {
            $modules[] = [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTS_SCROLL_ADDONS];
        }

        return $modules;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_SidebarHooks();
