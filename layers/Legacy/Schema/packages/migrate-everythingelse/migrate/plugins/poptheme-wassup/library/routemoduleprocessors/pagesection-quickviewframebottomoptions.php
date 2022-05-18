<?php

use PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoP_Module_QuickviewFrameBottomOptionsPageSectionRouteModuleProcessor extends PoP_Module_QuickviewFrameTopOptionsPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManagerFacade::getInstance();

        // Get the value of the SideInfo Content. If it is not the "Close Sideinfo" module, then we need to add support to toggle the SideInfo
        $load_module = true;
        if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
            $load_module = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_QUICKVIEW] == $pop_module_routemoduleprocessor_manager->getRouteModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
        }

        if ($load_module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][] = [
                'module' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG]
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_QuickviewFrameBottomOptionsPageSectionRouteModuleProcessor()
	);
}, 200);
