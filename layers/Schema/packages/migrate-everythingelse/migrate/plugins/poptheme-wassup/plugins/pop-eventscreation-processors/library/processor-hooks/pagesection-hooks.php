<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_EventsCreation_PageSectionHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:addons',
            array($this, 'initModelPropsAddons'),
            10,
            3
        );
    }

    public function initModelPropsAddons(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $submodules = array(
                        [GD_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_EM_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENT_CREATE],
                        [GD_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_EM_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENT_UPDATE],
                    );
                    foreach ($submodules as $submodule) {
                        $processor->setProp($submodule, $props, 'title', '');
                    }
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_EventsCreation_PageSectionHooks();
