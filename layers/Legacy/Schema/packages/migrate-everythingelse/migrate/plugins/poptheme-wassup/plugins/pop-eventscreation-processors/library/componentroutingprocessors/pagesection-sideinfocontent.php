<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_EventsCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [PoP_EventsCreation_Module_Processor_SidebarMultiples::class, PoP_EventsCreation_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYEVENTS_SIDEBAR],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_SidebarMultiples::class, PoP_EventsCreation_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYPASTEVENTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_EventsCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
