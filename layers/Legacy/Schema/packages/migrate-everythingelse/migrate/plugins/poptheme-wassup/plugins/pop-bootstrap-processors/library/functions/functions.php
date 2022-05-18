<?php

/**
 * Uniqueblocks
 */
\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getWassupBootstrapFramecomponentModules');
function getWassupBootstrapFramecomponentComponentVariations($componentVariations)
{
    $componentVariations[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED];
    $componentVariations[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API];
    $componentVariations[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_COPYSEARCHURL];

    return $componentVariations;
}
