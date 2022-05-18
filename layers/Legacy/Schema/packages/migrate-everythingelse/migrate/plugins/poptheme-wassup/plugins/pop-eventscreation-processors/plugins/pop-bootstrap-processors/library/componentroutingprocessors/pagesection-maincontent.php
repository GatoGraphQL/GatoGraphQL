<?php

use PoP\Root\Routing\RequestNature;

class PoP_EventsCreation_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::class, PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYEVENTS],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::class, PoP_EventsCreation_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYPASTEVENTS],
        );
        foreach ($routemodules as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
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
		new PoP_EventsCreation_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
