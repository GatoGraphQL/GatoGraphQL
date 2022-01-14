<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonPages_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE => [GD_CommonPages_Module_Processor_CustomGroups::class, GD_CommonPages_Module_Processor_CustomGroups::MODULE_GROUP_WHOWEARE],
            POP_COMMONPAGES_ROUTE_ABOUT => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_ABOUT],
        );
        foreach ($modules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_CommonPages_Module_MainContentRouteModuleProcessor()
	);
}, 200);
