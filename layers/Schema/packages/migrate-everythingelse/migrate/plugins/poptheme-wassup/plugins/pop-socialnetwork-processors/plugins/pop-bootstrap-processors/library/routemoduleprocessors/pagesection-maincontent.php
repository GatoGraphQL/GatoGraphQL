<?php

use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;

class PoPTheme_Wassup_SocialNetwork_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWERS],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORFOLLOWINGUSERS],
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Tag route modules
        $routemodules = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_Module_Processor_TagTabPanelSectionBlocks::class, PoP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGSUBSCRIBERS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Single route modules
        $routemodules = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_SINGLERECOMMENDEDBY],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_SINGLEUPVOTEDBY],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_Module_Processor_SingleTabPanelSectionBlocks::class, PoP_Module_Processor_SingleTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_SINGLEDOWNVOTEDBY],
        );
        foreach ($routemodules as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new PoPTheme_Wassup_SocialNetwork_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
