<?php

use PoP\Root\Routing\RouteNatures;

class PoPTheme_Wassup_AddComments_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => [PoP_Module_Processor_CommentsBlocks::class, PoP_Module_Processor_CommentsBlocks::MODULE_BLOCK_ADDCOMMENT],
        );
        foreach ($routemodules as $route => $module) {
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
		new PoPTheme_Wassup_AddComments_Module_MainContentRouteModuleProcessor()
	);
}, 200);
