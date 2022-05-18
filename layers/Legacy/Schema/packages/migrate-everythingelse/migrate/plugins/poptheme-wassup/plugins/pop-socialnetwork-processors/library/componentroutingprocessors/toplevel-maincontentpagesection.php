<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_SocialNetwork_Module_ContentPageSectionTopLevelComponentRoutingProcessor extends PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWUSER,
            POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER,
            POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
            POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG,
            POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG,
            POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
            POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST,
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
            POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::MODULE_PAGESECTIONCONTAINER_HOLE],
                'conditions' => [
                    'target' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
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
		new PoPTheme_Wassup_SocialNetwork_Module_ContentPageSectionTopLevelComponentRoutingProcessor()
	);
}, 200);
