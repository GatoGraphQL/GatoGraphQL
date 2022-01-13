<?php

use PoP\Routing\RouteNatures;

class PoP_EventLinksCreation_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENTLINK_CREATE],
            POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENTLINK_UPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_EventLinksCreation_Module_MainContentRouteModuleProcessor()
	);
}, 200);
