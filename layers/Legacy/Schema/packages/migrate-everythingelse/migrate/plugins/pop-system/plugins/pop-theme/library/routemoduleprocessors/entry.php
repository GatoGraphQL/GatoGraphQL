<?php

use PoP\Routing\RouteNatures;

class PoPSystem_Theme_Module_EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME => [PoP_System_Theme_Module_Processor_SystemActions::class, PoP_System_Theme_Module_Processor_SystemActions::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME],
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
    new PoPSystem_Theme_Module_EntryRouteModuleProcessor()
	);
}, 200);
