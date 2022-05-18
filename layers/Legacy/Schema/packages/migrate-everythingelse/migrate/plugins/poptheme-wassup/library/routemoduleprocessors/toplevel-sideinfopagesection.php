<?php

class PoP_Module_SideInfoPageSectionTopLevelRouteModuleProcessor extends PoP_Module_SideInfoPageSectionTopLevelRouteModuleProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
        	'module' => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEWSIDEINFO],
        	'conditions' => [
	            'target' => POP_TARGET_QUICKVIEW,
	        ],
        ];
        $ret[] = [
        	'module' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODYSIDEINFO],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_SideInfoPageSectionTopLevelRouteModuleProcessor()
	);
}, 200);
