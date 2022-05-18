<?php

class PoPTheme_UserStance_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:addons',
            $this->initModelPropsAddons(...),
            10,
            3
        );
    }

    public function initModelPropsAddons(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component[1]) {
            case PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS:
                $subComponents = array(
                    [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_STANCE_CREATE],
                    [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_STANCE_UPDATE],
                );
                foreach ($subComponents as $subComponent) {
                    $processor->setProp($subComponent, $props, 'title', '');
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_UserStance_PageSectionHooks();
