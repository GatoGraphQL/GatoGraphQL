<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_CPLC_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => [PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_MYLINKS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
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
		new PoPTheme_Wassup_CPLC_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
