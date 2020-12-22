<?php

use PoP\Routing\RouteNatures;

class MultiDomain_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_MULTIDOMAIN_ROUTE_EXTERNAL => [PoP_Module_Processor_MultidomainCodes::class, PoP_Module_Processor_MultidomainCodes::MODULE_CODE_EXTERNAL],
        );
        foreach ($modules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new MultiDomain_Module_MainContentRouteModuleProcessor()
	);
}, 200);
