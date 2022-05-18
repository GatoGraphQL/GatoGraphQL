<?php

use PoP\Root\Routing\RequestNature;

class PoP_CommonPages_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE => [GD_CommonPages_Module_Processor_CustomGroups::class, GD_CommonPages_Module_Processor_CustomGroups::COMPONENT_GROUP_WHOWEARE],
            POP_COMMONPAGES_ROUTE_ABOUT => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_BODY_ABOUT],
        );
        foreach ($components as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_CommonPages_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
