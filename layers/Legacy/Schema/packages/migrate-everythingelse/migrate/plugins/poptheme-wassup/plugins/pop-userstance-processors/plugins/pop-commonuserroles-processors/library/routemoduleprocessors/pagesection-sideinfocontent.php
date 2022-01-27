<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserStance_CommonUserRoles_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR]
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
		new PoPTheme_Wassup_UserStance_CommonUserRoles_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
