<?php

class PoPTheme_Wassup_ApplicationProcessors_WebPlatform_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:sideinfo',
            $this->initModelPropsHover(...),
            10,
            3
        );
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:addons',
            $this->initModelPropsAddons(...),
            10,
            3
        );
    }

    public function initModelPropsHover(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        $processor->mergeJsmethodsProp([PoP_ContactUs_Module_Processor_Blocks::class, PoP_ContactUs_Module_Processor_Blocks::MODULE_BLOCK_CONTACTUS], $props, array('resetOnUserLogout'));
    }

    public function initModelPropsAddons(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component[1]) {
            case PoP_Module_Processor_TabPanes::MODULE_PAGESECTION_ADDONS:
                $processor->mergeJsmethodsProp([PoP_Module_Processor_CommentsBlocks::class, PoP_Module_Processor_CommentsBlocks::MODULE_BLOCK_ADDCOMMENT], $props, array('destroyPageOnSuccess'));
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ApplicationProcessors_WebPlatform_PageSectionHooks();
