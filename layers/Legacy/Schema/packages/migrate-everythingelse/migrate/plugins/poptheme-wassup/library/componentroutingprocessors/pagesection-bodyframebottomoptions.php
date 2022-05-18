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

        $pop_componentVariation_componentroutingprocessor_manager = ComponentRoutingProcessorManagerFacade::getInstance();

        // Get the value of the SideInfo Content. If it is not the "Close Sideinfo" module, then we need to add support to toggle the SideInfo
        $load_componentVariation = true;
        if (PoPThemeWassup_Utils::checkLoadingPagesectionModule()) {
            $load_componentVariation = [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_BODY] == $pop_componentVariation_componentroutingprocessor_manager->getRoutingComponentByMostAllMatchingStateProperties(POP_PAGEMODULEGROUP_TOPLEVEL_CONTENTPAGESECTION);
        }

        if ($load_componentVariation) {
            $ret[CustomPostRequestNature::CUSTOMPOST][] = [
                'component-variation' => [PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_CLOSEPAGEBTNBIG]
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
