<?php

use PoP\Routing\RouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoPTheme_Wassup_SP_EM_Bootstrap_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $routemodules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_SectionTabPanelBlocks::class, PoP_LocationPosts_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_LOCATIONPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelBlocks::class, PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_TagSectionTabPanelBlocks::class, PoP_LocationPosts_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGLOCATIONPOSTS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
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
		new PoPTheme_Wassup_SP_EM_Bootstrap_Module_MainContentRouteModuleProcessor()
	);
}, 200);
