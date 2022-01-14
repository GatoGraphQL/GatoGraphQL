<?php

use PoP\Root\Routing\RequestNature;

class Wassup_Newsletter_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_NEWSLETTER_ROUTE_NEWSLETTER => [PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::MODULE_BLOCK_NEWSLETTER],
            POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::MODULE_BLOCK_NEWSLETTERUNSUBSCRIPTION],
        );
        foreach ($routemodules as $route => $module) {
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
		new Wassup_Newsletter_Module_MainContentRouteModuleProcessor()
	);
}, 200);
