<?php

class PoP_Module_TopContentPageSectionRouteModuleProcessor extends PoP_Module_TopContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $thememode_modules = array(
            GD_THEMEMODE_WASSUP_SIMPLE => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_TOPSIMPLE],
            GD_THEMEMODE_WASSUP_EMBED => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_TOPEMBED],
        );
        foreach ($thememode_modules as $thememode => $module) {
            $ret[] = [
                'module' => $module,
                'conditions' => [
                    'thememode' => $thememode,
                ],
            ];
        }

        // Default for everything
        $ret[] = [
            'module' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_TOP],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_TopContentPageSectionRouteModuleProcessor()
	);
}, 200);
