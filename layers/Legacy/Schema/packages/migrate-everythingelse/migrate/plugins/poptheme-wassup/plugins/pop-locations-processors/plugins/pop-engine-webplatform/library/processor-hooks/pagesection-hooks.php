<?php

class PoP_Locations_WebPlatform_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
            $this->initModelProps(...),
            10,
            3
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component->name) {
            case PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_MODALS:
                $processor->mergeJsmethodsProp([PoP_Module_Processor_LocationsMapBlocks::class, PoP_Module_Processor_LocationsMapBlocks::COMPONENT_BLOCK_STATICLOCATIONSMAP], $props, array('modalMapBlock'));
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_Locations_WebPlatform_PageSectionHooks();
