<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoPTheme_Wassup_RelatedPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_RelatedPosts_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
