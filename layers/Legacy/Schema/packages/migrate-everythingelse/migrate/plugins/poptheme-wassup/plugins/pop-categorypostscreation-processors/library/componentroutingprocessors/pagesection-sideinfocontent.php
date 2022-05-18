<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_CategoryPostsCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
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
            $ret[RequestNature::GENERIC][$route][] = [
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
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_CategoryPostsCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
