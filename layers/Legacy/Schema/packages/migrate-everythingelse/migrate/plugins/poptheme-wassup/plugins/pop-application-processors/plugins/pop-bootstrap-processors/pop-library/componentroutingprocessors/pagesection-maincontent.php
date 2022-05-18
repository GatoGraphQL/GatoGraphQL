<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_ApplicationProcessors_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_HIGHLIGHTS],
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_MYHIGHLIGHTS],
            POP_POSTSCREATION_ROUTE_MYPOSTS => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_MYPOSTS],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routeComponents = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SingleSectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_SingleSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routeComponents = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelBlocks::class, PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORHIGHLIGHTS],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
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
		new PoPTheme_Wassup_ApplicationProcessors_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
