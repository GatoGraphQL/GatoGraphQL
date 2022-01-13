<?php

use PoP\Routing\RouteNatures;

class PoP_Application_ClusterCommonPages_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS => [GD_ClusterCommonPages_Module_Processor_CustomGroups::class, GD_ClusterCommonPages_Module_Processor_CustomGroups::MODULE_GROUP_OURSPONSORS],
        );
        foreach ($modules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Application_ClusterCommonPages_Module_MainContentRouteModuleProcessor()
	);
}, 200);
