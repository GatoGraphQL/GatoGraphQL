<?php

class PoP_Module_SideInfoFrameOptionsPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoFrameOptionsPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        // Default
        $ret[] = [
        	'component' => [GD_Wassup_Module_Processor_AnchorControls::class, GD_Wassup_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_SideInfoFrameOptionsPageSectionComponentRoutingProcessor()
	);
}, 200);
