<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserStance_CommonUserRoles_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
            POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => [PoPVP_Module_Processor_SidebarMultiples::class, PoPVP_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR]
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
		new PoPTheme_Wassup_UserStance_CommonUserRoles_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
