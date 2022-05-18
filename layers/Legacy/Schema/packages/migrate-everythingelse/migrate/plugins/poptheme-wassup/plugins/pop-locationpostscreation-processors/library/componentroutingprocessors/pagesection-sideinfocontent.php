<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_LocationPostsCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $componentVariations = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [PoPSPEM_Module_Processor_SidebarMultiples::class, PoPSPEM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_LocationPostsCreation_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
