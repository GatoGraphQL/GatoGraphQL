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

    public function initModelPropsHover(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        
        $subComponents = array(
            [GD_URE_Module_Processor_CreateProfileBlocks::class, GD_URE_Module_Processor_CreateProfileBlocks::COMPONENT_BLOCK_PROFILEORGANIZATION_CREATE],
            [GD_URE_Module_Processor_CreateProfileBlocks::class, GD_URE_Module_Processor_CreateProfileBlocks::COMPONENT_BLOCK_PROFILEINDIVIDUAL_CREATE],
        );
        foreach ($subComponents as $subComponent) {
            $processor->mergeJsmethodsProp($subComponent, $props, array('resetOnSuccess'));
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_URE_PageSectionHooks();
