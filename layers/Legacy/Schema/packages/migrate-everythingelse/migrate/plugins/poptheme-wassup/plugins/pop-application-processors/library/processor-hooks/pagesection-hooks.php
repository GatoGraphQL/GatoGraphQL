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

    public function initModelPropsAddons(\PoP\ComponentModel\Component\Component $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component->name) {
            case PoP_Module_Processor_TabPanes::COMPONENT_PAGESECTION_ADDONS:
                $subcomponents = array(
                    [PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_HIGHLIGHT_CREATE],
                    [PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::class, PoP_AddHighlights_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_HIGHLIGHT_UPDATE],
                );
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $subcomponents = array_merge(
                        $subcomponents,
                        array(
                            [PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_POST_CREATE],
                            [PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_PostsCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_POST_UPDATE],
                        )
                    );
                }
                foreach ($subcomponents as $subcomponent) {
                    $processor->setProp($subcomponent, $props, 'title', '');
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ApplicationProcessors_PageSectionHooks();
