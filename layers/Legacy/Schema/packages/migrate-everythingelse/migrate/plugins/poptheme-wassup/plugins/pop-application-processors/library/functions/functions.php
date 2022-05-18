<?php

/**
 * Uniqueblocks
 */
\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getDomainFramecomponentModules');
function getDomainFramecomponentComponentVariations($componentVariations)
{
    $componentVariations[] = [PoP_MultidomainProcessors_Module_Processor_Dataloads::class, PoP_MultidomainProcessors_Module_Processor_Dataloads::MODULE_DATALOAD_INITIALIZEDOMAIN];
    $componentVariations[] = [PoP_Module_Processor_GFModalComponents::class, PoP_Module_Processor_GFModalComponents::MODULE_MODAL_SHAREBYEMAIL];
    return $componentVariations;
}
