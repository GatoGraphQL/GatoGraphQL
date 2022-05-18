<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_ContentPostLinksCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_SidebarMultiples::class, PoP_ContentPostLinksCreation_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYCONTENTPOSTLINKS_SIDEBAR],
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
		new PoPTheme_Wassup_ContentPostLinksCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
