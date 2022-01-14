<?php

use PoP\Root\Routing\RouteNatures;

class PoP_EventsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::class, PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYEVENTS],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::class, PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYPASTEVENTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_EventsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
