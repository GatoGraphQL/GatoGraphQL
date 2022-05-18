<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;

class PoPTheme_Wassup_SocialNetwork_Bootstrap_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWERS],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Tag route modules
        $routeComponents = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_Module_Processor_TagTabPanelSectionBlocks::class, PoP_Module_Processor_TagTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_TAGSUBSCRIBERS],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Single route modules
        $routeComponents = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_SINGLERECOMMENDEDBY],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_SINGLEUPVOTEDBY],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::COMPONENT_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
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
		new PoPTheme_Wassup_SocialNetwork_Bootstrap_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
