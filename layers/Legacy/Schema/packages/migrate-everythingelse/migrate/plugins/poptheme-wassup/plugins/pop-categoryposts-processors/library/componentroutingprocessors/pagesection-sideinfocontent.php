<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_CategoryPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
        );
        foreach ($routes as $route) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR]
            ];
        }
        foreach ($routes as $route) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component-variation' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR]
            ];
        }
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR]
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
		new PoPTheme_Wassup_CategoryPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
