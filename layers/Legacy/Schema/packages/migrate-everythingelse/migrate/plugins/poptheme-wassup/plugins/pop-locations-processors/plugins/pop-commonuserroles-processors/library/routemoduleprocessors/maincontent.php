<?php

use PoP\Routing\RouteNatures;

class Wassup_EM_URE_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules_addons = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_CustomScrollMapSectionBlocks::class, GD_URE_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_ORGANIZATIONS_SCROLLMAP],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_CustomScrollMapSectionBlocks::class, GD_URE_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_INDIVIDUALS_SCROLLMAP],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MAP) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new Wassup_EM_URE_Module_MainContentRouteModuleProcessor()
	);
}, 200);
