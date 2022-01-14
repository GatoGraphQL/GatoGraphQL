<?php

use PoP\Root\Routing\RouteNatures;

class PoP_NoSearchCategoryPostsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS00 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS00],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS01 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS01],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS02 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS02],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS03 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS03],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS04 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS04],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS05 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS05],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS06 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS06],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS07 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS07],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS08 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS08],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS09 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS09],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS10 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS10],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS11 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS11],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS12 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS12],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS13 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS13],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS14 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS14],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS15 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS15],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS16 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS16],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS17 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS17],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS18 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS18],
            POP_NOSEARCHCATEGORYPOSTSCREATION_ROUTE_MYNOSEARCHCATEGORYPOSTS19 => [LPPC_Module_Processor_SectionTabPanelBlocks::class, LPPC_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYCATEGORYPOSTS19],
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
		new PoP_NoSearchCategoryPostsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
