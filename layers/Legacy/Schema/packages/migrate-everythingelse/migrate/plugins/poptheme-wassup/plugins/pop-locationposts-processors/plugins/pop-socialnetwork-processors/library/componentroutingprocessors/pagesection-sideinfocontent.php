<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoPTheme_Wassup_SocialNetwork_SocialNetwork_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_LocationPosts_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_LocationPosts_SocialNetwork_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
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
		new PoPTheme_Wassup_SocialNetwork_SocialNetwork_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
