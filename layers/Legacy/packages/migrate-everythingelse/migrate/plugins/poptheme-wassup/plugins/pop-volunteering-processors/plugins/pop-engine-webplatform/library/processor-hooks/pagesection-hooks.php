<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_Volunteering_WebPlatform_PageSectionHooks
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
                $processor->mergeJsmethodsProp([PoP_Volunteering_Module_Processor_Blocks::class, PoP_Volunteering_Module_Processor_Blocks::MODULE_BLOCK_VOLUNTEER], $props, array('destroyPageOnSuccess'));
                $processor->mergeProp(
                    [PoP_Volunteering_Module_Processor_Blocks::class, PoP_Volunteering_Module_Processor_Blocks::MODULE_BLOCK_VOLUNTEER],
                    $props,
                    'params',
                    array(
                        'data-destroytime' => 3000
                    )
                );
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Volunteering_WebPlatform_PageSectionHooks();
