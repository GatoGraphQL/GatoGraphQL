<?php

use PoP\Routing\RouteNatures;

class PoP_CommonUserRolesProcessors_Bootstrap_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => [GD_URE_Module_Processor_SectionTabPanelBlocks::class, GD_URE_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_ORGANIZATIONS],
            POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => [GD_URE_Module_Processor_SectionTabPanelBlocks::class, GD_URE_Module_Processor_SectionTabPanelBlocks::MODULE_BLOCK_TABPANEL_INDIVIDUALS],
        );
        foreach ($modules as $route => $module) {
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
		new PoP_CommonUserRolesProcessors_Bootstrap_Module_MainContentRouteModuleProcessor()
	);
}, 200);
