<?php

use PoP\Routing\RouteNatures;

class PoP_CategoryPostsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Page modules
        $routemodules = array(
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18],
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19],
        );

        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
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
		new PoP_CategoryPostsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
