<?php

use PoP\ComponentModel\Component\Component;

class PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessor extends PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        // The modules below are chosen when the correspoding target is set
        $target_components = array(
            POP_TARGET_SIDE => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_SIDE],
            POP_TARGET_TOP => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_TOP],
            POP_TARGET_BACKGROUND => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_BACKGROUND],
            POP_TARGET_NAVIGATOR => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_NAVIGATOR],
            POP_TARGET_FRAMECOMPONENTS => [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_FRAMECOMPONENTS],
            POP_TARGET_ADDONS => [PoP_Module_Processor_Windows::class, PoP_Module_Processor_Windows::COMPONENT_WINDOW_ADDONS],
            POP_TARGET_MODALS => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::COMPONENT_PAGESECTIONCONTAINER_MODALS],
            POP_TARGET_QUICKVIEW => [PoP_Module_Processor_Modals::class, PoP_Module_Processor_Modals::COMPONENT_MODAL_QUICKVIEW],
            POP_TARGET_HOVER => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_HOVER],
            POP_TARGET_HOLE => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::COMPONENT_PAGESECTIONCONTAINER_HOLE],
        );
        foreach ($target_components as $target => $component) {
            $ret[] = [
                'component' => $component,
                'conditions' => [
                    'target' => $target,
                ],
            ];
        }

        $ret[] = [
            'component' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_BODY],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessor()
	);
}, 200);
