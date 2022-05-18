<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_SocialNetwork_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
        }

        $components = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
        }

        $components = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = ['component' => $component];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_SocialNetwork_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
