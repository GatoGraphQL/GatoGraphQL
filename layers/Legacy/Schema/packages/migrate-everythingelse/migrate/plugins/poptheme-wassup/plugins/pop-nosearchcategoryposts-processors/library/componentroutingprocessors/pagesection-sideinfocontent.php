<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_NoSearchCategoryPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18,
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19,
        );
        foreach ($routes as $route) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR]
            ];
        }
        foreach ($routes as $route) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR]
            ];
        }
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR]
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_NoSearchCategoryPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
