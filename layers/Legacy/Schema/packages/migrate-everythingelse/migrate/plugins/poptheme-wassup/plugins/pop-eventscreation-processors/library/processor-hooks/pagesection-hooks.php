<?php

class PoP_EventsCreation_PageSectionHooks
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
        switch ($component[1]) {
            case PoP_Module_Processor_TabPanes::COMPONENT_PAGESECTION_ADDONS:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    $subComponents = array(
                        [GD_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_EM_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_EVENT_CREATE],
                        [GD_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_EM_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_EVENT_UPDATE],
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
new PoP_EventsCreation_PageSectionHooks();
