<?php

use PoP\Root\Routing\RequestNature;

class PoP_LocationPostsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [PoP_LocationPostsCreation_Module_Processor_SectionTabPanelBlock::class, PoP_LocationPostsCreation_Module_Processor_SectionTabPanelBlock::MODULE_BLOCK_TABPANEL_MYLOCATIONPOSTS],
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
		new PoP_LocationPostsCreation_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
