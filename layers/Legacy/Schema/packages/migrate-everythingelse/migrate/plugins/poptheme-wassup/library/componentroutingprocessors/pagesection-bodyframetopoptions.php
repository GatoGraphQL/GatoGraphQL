<?php

use PoP\ComponentModel\Component\Component;
use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_BodyFrameTopOptionsPageSectionComponentRoutingProcessor extends PoP_Module_BodyFrameTopOptionsPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        // Get the value of the SideInfo Content. If it is not the "Close Sideinfo" block, then we need to add support to toggle the SideInfo
        $load_component = true;
        if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
            $load_component = [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_BODY] == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
        }

        if ($load_component && ($sideinfo_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_PAGESECTION_SIDEINFOCONTENT))) {
            if ($sideinfo_component == [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_EMPTYSIDEINFO]) {
                $ret[] = [
                    'component' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN],
                ];
            } else {
                $ret[] = [
                    'component' => [GD_Wassup_Module_Processor_DropdownButtonControls::class, GD_Wassup_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE],
                ];
            }
        } else {
            $ret[] = [
                'component' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_BodyFrameTopOptionsPageSectionComponentRoutingProcessor()
	);
}, 200);
