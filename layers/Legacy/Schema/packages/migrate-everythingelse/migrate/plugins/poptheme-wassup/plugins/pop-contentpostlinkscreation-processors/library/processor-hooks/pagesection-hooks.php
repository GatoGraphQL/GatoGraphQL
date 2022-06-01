<?php

class PoPTheme_Wassup_ContentPostLinksProcessors_PageSectionHooks
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
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $subcomponents = array(
                        [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_CONTENTPOSTLINK_CREATE],
                        [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_CONTENTPOSTLINK_UPDATE],
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
new PoPTheme_Wassup_ContentPostLinksProcessors_PageSectionHooks();
