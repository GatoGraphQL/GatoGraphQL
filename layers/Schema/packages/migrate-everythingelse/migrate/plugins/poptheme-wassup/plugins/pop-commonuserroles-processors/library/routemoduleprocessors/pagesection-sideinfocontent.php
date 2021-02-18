<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_CommonUserRoles_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_SidebarMultiples::class, GD_URE_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_SidebarMultiples::class, GD_URE_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR],
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_CommonUserRoles_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
