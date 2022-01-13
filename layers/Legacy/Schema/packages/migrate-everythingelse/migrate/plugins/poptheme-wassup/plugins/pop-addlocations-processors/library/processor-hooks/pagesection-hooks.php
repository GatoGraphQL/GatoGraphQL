<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddLocations_PageSectionHooks
{
    public function __construct()
    {
        // HooksAPIFacade::getInstance()->addFilter(
        //     'PoP_Module_Processor_CustomModalPageSections:getDialogClasses:modals',
        //     array($this, 'modalDialogClasses')
        // );
        // HooksAPIFacade::getInstance()->addFilter(
        //     'PoP_Module_Processor_CustomModalPageSections:getHeaderTitles:modals',
        //     array($this, 'modalHeaderTitles')
        // );
        HooksAPIFacade::getInstance()->addAction(
            'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
            array($this, 'initModelProps'),
            10,
            3
        );
    }

    // public function modalHeaderTitles($headerTitles)
    // {
    //     return array_merge(
    //         $headerTitles,
    //         array(
    //             GD_EM_Module_Processor_CreateLocationBlocks::MODULE_BLOCK_CREATELOCATION => sprintf(
    //                 '<i class="fa fa-fw fa-map-marker"></i>%s',
    //                 TranslationAPIFacade::getInstance()->__('Add location', 'poptheme-wassup')
    //             ),
    //         )
    //     );
    // }

    // public function modalDialogClasses($classes)
    // {
    //     return array_merge(
    //         $classes,
    //         array(
    //             GD_EM_Module_Processor_CreateLocationBlocks::MODULE_BLOCK_CREATELOCATION => 'modal-lg',
    //         )
    //     );
    // }

    public function initModelProps(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS:
                $processor->setProp([GD_EM_Module_Processor_CreateLocationBlocks::class, GD_EM_Module_Processor_CreateLocationBlocks::MODULE_BLOCK_CREATELOCATION], $props, 'title', '');
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_AddLocations_PageSectionHooks();
