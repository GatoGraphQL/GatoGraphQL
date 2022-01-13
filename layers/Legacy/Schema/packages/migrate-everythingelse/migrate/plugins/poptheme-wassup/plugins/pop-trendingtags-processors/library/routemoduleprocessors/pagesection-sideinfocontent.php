<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_TrendingTags_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
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
		new PoPTheme_Wassup_TrendingTags_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
