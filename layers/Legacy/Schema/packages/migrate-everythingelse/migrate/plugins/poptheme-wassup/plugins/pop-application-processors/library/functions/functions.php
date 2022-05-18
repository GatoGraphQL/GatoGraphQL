<?php

/**
 * Uniqueblocks
 */
\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getDomainFramecomponentModules');
function getDomainFramecomponentComponents($components)
{
    $components[] = [PoP_MultidomainProcessors_Module_Processor_Dataloads::class, PoP_MultidomainProcessors_Module_Processor_Dataloads::COMPONENT_DATALOAD_INITIALIZEDOMAIN];
    $components[] = [PoP_Module_Processor_GFModalComponents::class, PoP_Module_Processor_GFModalComponents::COMPONENT_MODAL_SHAREBYEMAIL];
    return $components;
}
