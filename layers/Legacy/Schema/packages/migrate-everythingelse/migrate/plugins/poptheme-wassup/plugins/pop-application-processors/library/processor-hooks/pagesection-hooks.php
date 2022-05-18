<?php

class PoPTheme_Wassup_ApplicationProcessors_PageSectionHooks
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
                $subComponentVariations = array(
                    [PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_HIGHLIGHT_CREATE],
                    [PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_HIGHLIGHT_UPDATE],
                );
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $subComponentVariations = array_merge(
                        $subComponentVariations,
                        array(
                            [PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_POST_CREATE],
                            [PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_POST_UPDATE],
                        )
                    );
                }
                foreach ($subComponentVariations as $submodule) {
                    $processor->setProp($submodule, $props, 'title', '');
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ApplicationProcessors_PageSectionHooks();
