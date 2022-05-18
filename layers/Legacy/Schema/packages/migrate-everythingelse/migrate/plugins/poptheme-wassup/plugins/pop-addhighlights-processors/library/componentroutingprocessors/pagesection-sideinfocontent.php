<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoPTheme_Wassup_AddHighlights_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
        }

        $components = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'component' => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT,
                ],
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_AddHighlights_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
