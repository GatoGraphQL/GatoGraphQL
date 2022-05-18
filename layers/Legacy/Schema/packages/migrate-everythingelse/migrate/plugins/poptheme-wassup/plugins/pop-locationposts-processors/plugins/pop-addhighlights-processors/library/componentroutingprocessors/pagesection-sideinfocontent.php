<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoPTheme_Wassup_LocationPosts_AddHighlights_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_LocationPosts_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_LocationPosts_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_HIGHLIGHTSSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'routing' => [
                        'queried-object-post-type' => POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST,
                    ],
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
		new PoPTheme_Wassup_LocationPosts_AddHighlights_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
