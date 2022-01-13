<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Locations_WebPlatform_PageSectionHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
            array($this, 'initModelProps'),
            10,
            3
        );
    }

    public function initModelProps(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS:
                $processor->mergeJsmethodsProp([PoP_Module_Processor_LocationsMapBlocks::class, PoP_Module_Processor_LocationsMapBlocks::MODULE_BLOCK_STATICLOCATIONSMAP], $props, array('modalMapBlock'));
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_Locations_WebPlatform_PageSectionHooks();
