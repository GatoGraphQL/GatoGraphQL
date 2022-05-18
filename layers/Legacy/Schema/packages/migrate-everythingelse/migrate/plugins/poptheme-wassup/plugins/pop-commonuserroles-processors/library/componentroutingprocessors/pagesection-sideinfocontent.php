<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_CommonUserRoles_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_SidebarMultiples::class, GD_URE_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_ORGANIZATIONS_SIDEBAR],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_SidebarMultiples::class, GD_URE_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SECTION_INDIVIDUALS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_CommonUserRoles_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
