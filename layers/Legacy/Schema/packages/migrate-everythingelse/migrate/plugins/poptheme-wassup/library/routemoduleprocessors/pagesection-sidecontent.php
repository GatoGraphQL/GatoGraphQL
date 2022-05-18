<?php

class PoP_Module_SideContentPageSectionRouteModuleProcessor extends PoP_Module_SideContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
        	'module' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_SIDE],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_SideContentPageSectionRouteModuleProcessor()
	);
}, 200);
