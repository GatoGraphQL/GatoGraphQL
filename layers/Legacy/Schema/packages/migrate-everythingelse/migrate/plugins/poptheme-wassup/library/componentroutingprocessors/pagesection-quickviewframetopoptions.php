<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_QuickviewFrameTopOptionsPageSectionComponentRoutingProcessor extends PoP_Module_QuickviewFrameTopOptionsPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        // Get the value of the SideInfo Content. If it is not the "Close Sideinfo" block, then we need to add support to toggle the SideInfo
        $load_component = true;
        if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
            $load_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_QUICKVIEW] == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
        }

        if ($load_component && ($sideinfo_component = $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT))) {
            if ($sideinfo_component == [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_EMPTYSIDEINFO]) {
                $ret[] = [
                    'component' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN],
                ];
            } else {
                $ret[] = [
                    'component' => [GD_Wassup_Module_Processor_DropdownButtonControls::class, GD_Wassup_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_CLOSETOGGLE],
                ];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_QuickviewFrameTopOptionsPageSectionComponentRoutingProcessor()
	);
}, 200);
