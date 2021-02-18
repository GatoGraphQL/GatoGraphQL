<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_PoPSW_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL => [GD_Core_Module_Processor_HTMLCodes::class, GD_Core_Module_Processor_HTMLCodes::MODULE_CODE_APPSHELL],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_PoPSW_Module_MainContentRouteModuleProcessor()
	);
}, 200);
