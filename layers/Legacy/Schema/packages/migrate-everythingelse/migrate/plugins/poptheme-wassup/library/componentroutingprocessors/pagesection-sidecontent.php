<?php

class PoP_Module_SideContentPageSectionComponentRoutingProcessor extends PoP_Module_SideContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $ret[] = [
        	'component' => [PoPTheme_Wassup_Module_Processor_Frames::class, PoPTheme_Wassup_Module_Processor_Frames::COMPONENT_FRAME_SIDE],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_SideContentPageSectionComponentRoutingProcessor()
	);
}, 200);
