<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoP_Module_QuickviewFrameBottomOptionsPageSectionComponentRoutingProcessor extends PoP_Module_QuickviewFrameTopOptionsPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $pop_component_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        // Get the value of the SideInfo Content. If it is not the "Close Sideinfo" module, then we need to add support to toggle the SideInfo
        $load_component = true;
        if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
            $load_component = [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_QUICKVIEW] == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGECOMPONENTGROUP_TOPLEVEL_CONTENTPAGESECTION);
        }

        if ($load_component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][] = [
                'component' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG]
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
		new PoP_Module_QuickviewFrameBottomOptionsPageSectionComponentRoutingProcessor()
	);
}, 200);
