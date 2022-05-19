<?php

class PoPTheme_Wassup_EM_SidebarHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_SidebarMultiplesBase:modules',
            $this->getSidebarSubcomponents(...),
            0,
            4
        );
    }

    public function getSidebarSubcomponents($components, $screengroup, $screen, array $component)
    {

        // Add the Events Calendar to all Groups in the Sideinfo
        $includeScreengroups = \PoP\Root\App::applyFilters(
            'PoPTheme_Wassup_EM_SidebarHooks:sidebar_components:includeScreengroups',
            array(
                POP_SCREENGROUP_CONTENTREAD,
            )
        );
        if (in_array($screengroup, $includeScreengroups)/* && !in_array($screen, $exclude_screens)*/) {
            $components[] = [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTS_SCROLL_ADDONS];
        }

        return $components;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_SidebarHooks();
