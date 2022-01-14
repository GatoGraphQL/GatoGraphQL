<?php

use PoP\Root\Routing\RouteNatures;

class PoPSystem_Module_EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD => [PoP_System_Module_Processor_SystemActions::class, PoP_System_Module_Processor_SystemActions::MODULE_DATALOADACTION_SYSTEM_BUILD],
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE => [PoP_System_Module_Processor_SystemActions::class, PoP_System_Module_Processor_SystemActions::MODULE_DATALOADACTION_SYSTEM_GENERATE],
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL => [PoP_System_Module_Processor_SystemActions::class, PoP_System_Module_Processor_SystemActions::MODULE_DATALOADACTION_SYSTEM_INSTALL],
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
		new PoPSystem_Module_EntryRouteModuleProcessor()
	);
}, 200);
