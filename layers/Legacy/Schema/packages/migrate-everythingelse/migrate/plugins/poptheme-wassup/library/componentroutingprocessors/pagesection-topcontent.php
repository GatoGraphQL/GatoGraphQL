<?php

class PoP_Module_TopContentPageSectionComponentRoutingProcessor extends PoP_Module_TopContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $thememode_modules = array(
            GD_THEMEMODE_WASSUP_SIMPLE => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_TOPSIMPLE],
            GD_THEMEMODE_WASSUP_EMBED => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_TOPEMBED],
        );
        foreach ($thememode_modules as $thememode => $module) {
            $ret[] = [
                'component-variation' => $module,
                'conditions' => [
                    'thememode' => $thememode,
                ],
            ];
        }

        // Default for everything
        $ret[] = [
            'component-variation' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::MODULE_FRAME_TOP],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_TopContentPageSectionComponentRoutingProcessor()
	);
}, 200);
