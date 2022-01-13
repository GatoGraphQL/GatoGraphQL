<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_ContentPostLinksProcessors_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
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
                        [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_CONTENTPOSTLINK_CREATE],
                        [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_CONTENTPOSTLINK_UPDATE],
                    );
                }
                foreach ($submodules as $submodule) {
                    $processor->setProp($submodule, $props, 'title', '');
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ContentPostLinksProcessors_PageSectionHooks();
