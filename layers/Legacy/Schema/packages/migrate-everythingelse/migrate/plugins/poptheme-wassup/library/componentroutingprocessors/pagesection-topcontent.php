<?php

use PoP\ComponentModel\Component\Component;

class PoP_Module_TopContentPageSectionComponentRoutingProcessor extends PoP_Module_TopContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string,mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $thememode_components = array(
            GD_THEMEMODE_WASSUP_SIMPLE => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::COMPONENT_FRAME_TOPSIMPLE],
            GD_THEMEMODE_WASSUP_EMBED => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::COMPONENT_FRAME_TOPEMBED],
        );
        foreach ($thememode_components as $thememode => $component) {
            $ret[] = [
                'component' => $component,
                'conditions' => [
                    'thememode' => $thememode,
                ],
            ];
        }

        // Default for everything
        $ret[] = [
            'component' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::COMPONENT_FRAME_TOP],
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
