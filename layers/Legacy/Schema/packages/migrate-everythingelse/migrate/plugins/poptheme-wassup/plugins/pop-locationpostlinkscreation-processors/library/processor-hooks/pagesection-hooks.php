<?php

class PoP_LocationPostLinksCreation_PageSectionHooks
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
            case PoP_Module_Processor_TabPanes::COMPONENT_PAGESECTION_ADDONS:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $subComponents = array(
                        [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_LOCATIONPOSTLINK_CREATE],
                        [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_LOCATIONPOSTLINK_UPDATE],
                    );
                    foreach ($subComponents as $subComponent) {
                        $processor->setProp($subComponent, $props, 'title', '');
                    }
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinksCreation_PageSectionHooks();
