<?php

class PoP_EventLinksCreation_PageSectionHooks
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

    public function initModelPropsAddons(array $componentVariation, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $submodules = array(
                        [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENTLINK_CREATE],
                        [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENTLINK_UPDATE],
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
new PoP_EventLinksCreation_PageSectionHooks();
