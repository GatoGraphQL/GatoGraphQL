<?php

class PoP_Module_BackgroundContentPageSectionRouteModuleProcessor extends PoP_Module_BackgroundContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, string[]>
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new PoP_Module_BackgroundContentPageSectionRouteModuleProcessor()
	);
}, 200);
