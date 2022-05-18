<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_NSAppCatPro_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS00],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS01],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS02],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS03],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS04],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS05],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS06],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS07],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS08],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS09],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS10],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS11],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS12],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS13],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS14],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS15],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS16],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS17],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS18],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_SectionTabPanelBlocks::class, NSCPP_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_NOSEARCHCATEGORYPOSTS19],
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
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::class, NSCPP_Module_Processor_AuthorSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19],
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
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18],
            POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19 => [NSCPP_Module_Processor_TagSectionTabPanelBlocks::class, NSCPP_Module_Processor_TagSectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19],
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
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_NSAppCatPro_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
