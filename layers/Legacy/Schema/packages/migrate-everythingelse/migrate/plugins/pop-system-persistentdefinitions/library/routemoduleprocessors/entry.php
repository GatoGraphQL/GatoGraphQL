<?php

use PoP\Routing\RouteNatures;

class PoPSystem_PersistentDefinitions_Module_EntryRouteModuleProcessor extends \PoP\ModuleRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE => [PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions::class, PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE],
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
    new PoPSystem_PersistentDefinitions_Module_EntryRouteModuleProcessor()
	);
}, 200);
