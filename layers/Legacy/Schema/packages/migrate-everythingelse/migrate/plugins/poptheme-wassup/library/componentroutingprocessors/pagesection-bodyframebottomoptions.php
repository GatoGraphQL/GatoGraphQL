<?php

use PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class PoP_Module_BodyFrameBottomOptionsPageSectionComponentRoutingProcessor extends PoP_Module_BodyFrameTopOptionsPageSectionComponentRoutingProcessorBase
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
            $load_component = [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_BODY] == $pop_component_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
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
		new PoP_Module_BodyFrameBottomOptionsPageSectionComponentRoutingProcessor()
	);
}, 200);
