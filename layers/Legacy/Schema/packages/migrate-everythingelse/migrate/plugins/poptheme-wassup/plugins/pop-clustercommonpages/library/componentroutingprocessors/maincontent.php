<?php

use PoP\Root\Routing\RequestNature;

class PoP_Application_ClusterCommonPages_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS => [GD_ClusterCommonPages_Module_Processor_CustomGroups::class, GD_ClusterCommonPages_Module_Processor_CustomGroups::COMPONENT_GROUP_OURSPONSORS],
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
		new PoP_Application_ClusterCommonPages_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
