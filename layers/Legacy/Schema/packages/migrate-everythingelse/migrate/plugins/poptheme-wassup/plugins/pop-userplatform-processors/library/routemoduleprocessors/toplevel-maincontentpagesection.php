<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserPlatform_Module_ContentPageSectionTopLevelRouteModuleProcessor extends PoP_Module_ContentPageSectionTopLevelRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // The routes below open in the Hover
        $routes = array(
            POP_USERPLATFORM_ROUTE_SETTINGS,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_HOVER],
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
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_UserPlatform_Module_ContentPageSectionTopLevelRouteModuleProcessor()
	);
}, 200);
