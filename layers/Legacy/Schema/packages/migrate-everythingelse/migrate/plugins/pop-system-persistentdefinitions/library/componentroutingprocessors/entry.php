<?php

use PoP\Root\Routing\RequestNature;

class PoPSystem_PersistentDefinitions_Module_EntryComponentRoutingProcessor extends \PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE => [PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions::class, PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
    new PoPSystem_PersistentDefinitions_Module_EntryComponentRoutingProcessor()
	);
}, 200);
