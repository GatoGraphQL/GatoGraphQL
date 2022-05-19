<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Module_TabContentPageSectionComponentRoutingProcessor extends PoP_Module_TabContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $nature_components = array(
            RequestNature::HOME => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_HOME],
            UserRequestNature::USER => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_AUTHOR],
            CustomPostRequestNature::CUSTOMPOST => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_SINGLE],
            TagRequestNature::TAG => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_TAG],
            RequestNature::NOTFOUND => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_404],
            PageRequestNature::PAGE => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_PAGE],
            RequestNature::GENERIC => [PoP_Module_Processor_PageTabsLayouts::class, PoP_Module_Processor_PageTabsLayouts::COMPONENT_LAYOUT_PAGETABS_ROUTE],
        );
        foreach ($nature_components as $nature => $component) {
            $ret[$nature][] = [
                'component' => $component,
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
		new PoP_Module_TabContentPageSectionComponentRoutingProcessor()
	);
}, 200);
