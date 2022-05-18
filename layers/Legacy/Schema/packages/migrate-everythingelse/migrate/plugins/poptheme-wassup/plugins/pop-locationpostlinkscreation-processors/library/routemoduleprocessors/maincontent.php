<?php

use PoP\Root\Routing\RequestNature;

class PoP_LocationPostLinksCreation_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE],
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_LocationPostLinksCreation_Module_MainContentRouteModuleProcessor()
	);
}, 200);
