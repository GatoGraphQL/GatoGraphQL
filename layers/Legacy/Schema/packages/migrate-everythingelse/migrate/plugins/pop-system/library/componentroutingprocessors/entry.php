<?php

use PoP\Root\Routing\RequestNature;

class PoPSystem_Module_EntryComponentRoutingProcessor extends \PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD => [PoP_System_Module_Processor_SystemActions::class, PoP_System_Module_Processor_SystemActions::COMPONENT_DATALOADACTION_SYSTEM_BUILD],
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE => [PoP_System_Module_Processor_SystemActions::class, PoP_System_Module_Processor_SystemActions::COMPONENT_DATALOADACTION_SYSTEM_GENERATE],
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL => [PoP_System_Module_Processor_SystemActions::class, PoP_System_Module_Processor_SystemActions::COMPONENT_DATALOADACTION_SYSTEM_INSTALL],
        );
        foreach ($routemodules as $route => $component) {
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
		new PoPSystem_Module_EntryComponentRoutingProcessor()
	);
}, 200);
