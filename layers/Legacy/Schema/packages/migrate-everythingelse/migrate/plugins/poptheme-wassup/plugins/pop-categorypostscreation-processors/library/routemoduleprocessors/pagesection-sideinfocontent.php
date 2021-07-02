<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_CategoryPostsCreation_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18,
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19,
        );
        foreach ($routes as $route) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR]
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
		new PoPTheme_Wassup_CategoryPostsCreation_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
