<?php

use PoP\Root\Routing\RequestNature;

class PoP_LocationPostsCreation_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [PoP_LocationPostsCreation_Module_Processor_SectionTabPanelBlock::class, PoP_LocationPostsCreation_Module_Processor_SectionTabPanelBlock::COMPONENT_BLOCK_TABPANEL_MYLOCATIONPOSTS],
        );
        foreach ($routeComponents as $route => $component) {
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
		new PoP_LocationPostsCreation_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
