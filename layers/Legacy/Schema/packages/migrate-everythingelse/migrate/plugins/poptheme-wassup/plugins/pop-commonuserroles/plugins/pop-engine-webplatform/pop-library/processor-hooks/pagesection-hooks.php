<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_URE_PageSectionHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
            array($this, 'initModelPropsHover'),
            10,
            3
        );
    }

    public function initModelPropsHover(array $module, $props_in_array, $processor)
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
