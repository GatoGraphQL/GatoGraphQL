<?php

class PoPTheme_Wassup_URE_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
            $this->initModelPropsHover(...),
            10,
            3
        );
    }

    public function initModelPropsHover(array $componentVariation, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        
        $submodules = array(
            [GD_URE_Module_Processor_CreateProfileBlocks::class, GD_URE_Module_Processor_CreateProfileBlocks::MODULE_BLOCK_PROFILEORGANIZATION_CREATE],
            [GD_URE_Module_Processor_CreateProfileBlocks::class, GD_URE_Module_Processor_CreateProfileBlocks::MODULE_BLOCK_PROFILEINDIVIDUAL_CREATE],
        );
        foreach ($submodules as $submodule) {
            $processor->mergeJsmethodsProp($submodule, $props, array('resetOnSuccess'));
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_URE_PageSectionHooks();
