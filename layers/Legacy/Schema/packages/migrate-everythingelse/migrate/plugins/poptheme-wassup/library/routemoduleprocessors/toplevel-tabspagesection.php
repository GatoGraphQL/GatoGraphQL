<?php

class PoP_Module_TabsPageSectionTopLevelRouteModuleProcessor extends PoP_Module_TabsPageSectionTopLevelRouteModuleProcessorBase
{
    /**
     * @return array<string, string[]>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
        	'module' => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_ADDONTABS],
        	'conditions' => [
	            'target' => POP_TARGET_ADDONS,
	        ],
        ];

        $ret[] = [
        	'module' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODYTABS],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_TabsPageSectionTopLevelRouteModuleProcessor()
	);
}, 200);
