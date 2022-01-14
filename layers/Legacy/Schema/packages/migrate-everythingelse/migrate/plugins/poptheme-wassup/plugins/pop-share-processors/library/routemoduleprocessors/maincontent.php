<?php

use PoP\Root\Routing\RouteNatures;

class Wassup_Share_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SHARE_ROUTE_SHAREBYEMAIL => [PoP_Share_Module_Processor_Blocks::class, PoP_Share_Module_Processor_Blocks::MODULE_BLOCK_SHAREBYEMAIL],
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
		new Wassup_Share_Module_MainContentRouteModuleProcessor()
	);
}, 200);
