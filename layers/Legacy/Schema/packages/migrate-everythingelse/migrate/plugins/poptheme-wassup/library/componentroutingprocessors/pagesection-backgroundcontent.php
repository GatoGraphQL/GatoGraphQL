<?php

use PoP\ComponentModel\Component\Component;

class PoP_Module_BackgroundContentPageSectionComponentRoutingProcessor extends PoP_Module_BackgroundContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string,mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $ret[] = [
        	'component' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::COMPONENT_FRAME_BACKGROUND],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_BackgroundContentPageSectionComponentRoutingProcessor()
	);
}, 200);
