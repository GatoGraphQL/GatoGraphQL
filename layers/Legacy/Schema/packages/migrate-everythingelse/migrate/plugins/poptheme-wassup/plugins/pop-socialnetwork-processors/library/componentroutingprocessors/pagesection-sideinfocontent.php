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

        $componentVariations = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[TagRequestNature::TAG][$route][] = ['component-variation' => $componentVariation];
        }

        $componentVariations = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORFOLLOWERS_SIDEBAR],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORFOLLOWINGUSERS_SIDEBAR],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORSUBSCRIBEDTOTAGS_SIDEBAR],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORRECOMMENDEDPOSTS_SIDEBAR],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
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
