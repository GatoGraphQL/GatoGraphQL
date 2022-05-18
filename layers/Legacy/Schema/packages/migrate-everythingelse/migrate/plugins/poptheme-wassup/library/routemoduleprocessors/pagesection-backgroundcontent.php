<?php

class PoP_Module_BackgroundContentPageSectionRouteModuleProcessor extends PoP_Module_BackgroundContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $ret[] = [
        	'module' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_BACKGROUND],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_BackgroundContentPageSectionRouteModuleProcessor()
	);
}, 200);
