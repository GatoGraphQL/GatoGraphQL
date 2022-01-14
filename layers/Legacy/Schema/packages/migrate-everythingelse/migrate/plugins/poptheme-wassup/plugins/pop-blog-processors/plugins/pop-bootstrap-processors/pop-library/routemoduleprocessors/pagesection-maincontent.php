<?php

use PoP\Root\Routing\RequestNature;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;
use PoPSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_Blog_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Page modules
        $routemodules = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CONTENT],
            PostsComponentConfiguration::getPostsRoute() => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_POSTS],
            POP_BLOG_ROUTE_SEARCHCONTENT => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_SEARCHCONTENT],
            UsersComponentConfiguration::getUsersRoute() => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_USERS],
            POP_BLOG_ROUTE_SEARCHUSERS => [PoP_Module_Processor_TabPanelSectionBlocks::class, PoP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_SEARCHUSERS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Author route modules
        $routemodules = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCONTENT],
            PostsComponentConfiguration::getPostsRoute() => [PoP_Module_Processor_AuthorTabPanelSectionBlocks::class, PoP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Tag route modules
        $routemodules = array(
            POP_BLOG_ROUTE_CONTENT => [PoP_Module_Processor_TagTabPanelSectionBlocks::class, PoP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCONTENT],
            PostsComponentConfiguration::getPostsRoute() => [PoP_Module_Processor_TagTabPanelSectionBlocks::class, PoP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_Blog_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
