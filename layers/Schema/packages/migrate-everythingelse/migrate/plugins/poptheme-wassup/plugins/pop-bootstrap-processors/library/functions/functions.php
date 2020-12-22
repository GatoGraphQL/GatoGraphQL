<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Uniqueblocks
 */
HooksAPIFacade::getInstance()->addFilter('RequestUtils:getFramecomponentModules', 'getWassupBootstrapFramecomponentModules');
function getWassupBootstrapFramecomponentModules($modules)
{
    $modules[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED];
    $modules[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API];
    $modules[] = [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_COPYSEARCHURL];

    return $modules;
}
