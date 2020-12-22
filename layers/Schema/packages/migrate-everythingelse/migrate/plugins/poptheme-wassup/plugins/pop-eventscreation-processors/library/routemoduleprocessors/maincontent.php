<?php

use PoP\Routing\RouteNatures;

class PoP_EventsCreation_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_EVENTSCREATION_ROUTE_ADDEVENT => [GD_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_EM_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENT_CREATE],
            POP_EVENTSCREATION_ROUTE_EDITEVENT => [GD_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_EM_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENT_UPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
        }

        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_mycontent = array(
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [PoP_EventsCreation_Module_Processor_MySectionBlocks::class, PoP_EventsCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYEVENTS_TABLE_EDIT],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_MySectionBlocks::class, PoP_EventsCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYPASTEVENTS_TABLE_EDIT],
        );
        foreach ($routemodules_mycontent as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_TABLE) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_mycontent_simpleviewpreviews = array(
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [PoP_EventsCreation_Module_Processor_MySectionBlocks::class, PoP_EventsCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_MySectionBlocks::class, PoP_EventsCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_simpleviewpreviews as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_SIMPLEVIEW) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_mycontent_fullviewpreviews = array(
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [PoP_EventsCreation_Module_Processor_MySectionBlocks::class, PoP_EventsCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_MySectionBlocks::class, PoP_EventsCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_fullviewpreviews as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_FULLVIEW) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new PoP_EventsCreation_Module_MainContentRouteModuleProcessor()
	);
}, 200);
