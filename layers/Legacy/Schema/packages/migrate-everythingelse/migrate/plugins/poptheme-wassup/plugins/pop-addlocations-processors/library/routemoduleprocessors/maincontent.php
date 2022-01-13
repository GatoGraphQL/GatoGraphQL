<?php

use PoP\Routing\RouteNatures;

class PoP_AddLocations_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ADDLOCATIONS_ROUTE_ADDLOCATION => [GD_EM_Module_Processor_CreateLocationBlocks::class, GD_EM_Module_Processor_CreateLocationBlocks::MODULE_BLOCK_CREATELOCATION],
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
		new PoP_AddLocations_Module_MainContentRouteModuleProcessor()
	);
}, 200);
