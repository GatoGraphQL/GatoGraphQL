<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;

class PoP_Module_QuickviewFrameTopOptionsPageSectionComponentRoutingProcessor extends PoP_Module_QuickviewFrameTopOptionsPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        $pop_module_routemoduleprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        // Get the value of the SideInfo Content. If it is not the "Close Sideinfo" block, then we need to add support to toggle the SideInfo
        $load_module = true;
        if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
            $load_module = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEW] == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
        }

        if ($load_module && ($sideinfo_module = $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_PAGESECTION_SIDEINFOCONTENT))) {
            if ($sideinfo_module == [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_EMPTYSIDEINFO]) {
                $ret[] = [
                    'module' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_CLOSEPAGEBTN],
                ];
            } else {
                $ret[] = [
                    'module' => [GD_Wassup_Module_Processor_DropdownButtonControls::class, GD_Wassup_Module_Processor_DropdownButtonControls::MODULE_DROPDOWNBUTTONCONTROL_CLOSETOGGLE],
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
