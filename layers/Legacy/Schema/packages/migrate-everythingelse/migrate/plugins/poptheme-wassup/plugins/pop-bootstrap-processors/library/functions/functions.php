<?php

/**
 * Uniqueblocks
 */
\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getWassupBootstrapFramecomponentModules');
function getWassupBootstrapFramecomponentComponents($components)
{
    $components[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED];
    $components[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API];
    $components[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_COPYSEARCHURL];

    return $components;
}
