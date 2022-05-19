<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_ContentCreation_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_MYCONTENT],
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
		new PoPTheme_Wassup_ContentCreation_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
