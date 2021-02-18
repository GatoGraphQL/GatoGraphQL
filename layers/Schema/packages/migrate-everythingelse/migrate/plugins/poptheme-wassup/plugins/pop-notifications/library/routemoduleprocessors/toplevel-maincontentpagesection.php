<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_Notifications_Module_ContentPageSectionTopLevelRouteModuleProcessor extends PoP_Module_ContentPageSectionTopLevelRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
        );
        foreach ($routes as $route) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
                'conditions' => [
                    'target' => \PoP\ComponentModel\Constants\Targets::MAIN,
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
		new PoPTheme_Wassup_Notifications_Module_ContentPageSectionTopLevelRouteModuleProcessor()
	);
}, 200);
