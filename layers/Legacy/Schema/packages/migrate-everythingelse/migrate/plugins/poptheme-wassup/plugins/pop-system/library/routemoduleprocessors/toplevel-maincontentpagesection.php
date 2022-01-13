<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_System_Module_ContentPageSectionTopLevelRouteModuleProcessor extends PoP_Module_ContentPageSectionTopLevelRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD,
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE,
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL,
        );
        foreach ($routes as $route) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
                'conditions' => [
                    'target' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_System_Module_ContentPageSectionTopLevelRouteModuleProcessor()
	);
}, 200);
