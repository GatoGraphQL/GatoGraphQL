<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_CommonPages_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
        );
        foreach ($routes as $route) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR]
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
		new PoPTheme_Wassup_CommonPages_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
