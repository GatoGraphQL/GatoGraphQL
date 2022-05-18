<?php

class PoPTheme_Wassup_EM_WebPlatform_PageSectionHooks
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

    public function initModelProps(array $componentVariation, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS:
                $processor->mergeJsmethodsProp(
                    [GD_EM_Module_Processor_CreateLocationBlocks::class, GD_EM_Module_Processor_CreateLocationBlocks::MODULE_BLOCK_CREATELOCATION],
                    $props,
                    array(
                        'createLocationModalBlock',
                        'maybeCloseLocationModal',
                    )
                );
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_WebPlatform_PageSectionHooks();
