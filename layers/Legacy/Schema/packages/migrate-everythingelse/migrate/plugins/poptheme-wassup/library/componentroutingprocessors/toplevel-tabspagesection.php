<?php

class PoP_Module_TabsPageSectionTopLevelComponentRoutingProcessor extends PoP_Module_TabsPageSectionTopLevelComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $ret[] = [
        	'module' => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_ADDONTABS],
        	'conditions' => [
	            'target' => POP_TARGET_ADDONS,
	        ],
        ];

        $ret[] = [
        	'module' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODYTABS],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_TabsPageSectionTopLevelComponentRoutingProcessor()
	);
}, 200);
