<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Locations_PageSectionHooks
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
        // HooksAPIFacade::getInstance()->addFilter(
        //     'PoP_Module_Processor_CustomModalPageSections:getBodyClasses:modals',
        //     array($this, 'modalBodyClasses')
        // );
    }

    // public function modalBodyClasses($classes)
    // {

    //     // Do not add the 'modal-body' for the map
    //     unset($classes[PoP_Module_Processor_LocationsMapBlocks::MODULE_BLOCK_STATICLOCATIONSMAP]);
    //     return $classes;
    // }

    // public function modalHeaderTitles($headerTitles)
    // {
    //     return array_merge(
    //         $headerTitles,
    //         array(
    //             PoP_Module_Processor_LocationsMapBlocks::MODULE_BLOCK_STATICLOCATIONSMAP => TranslationAPIFacade::getInstance()->__('Location(s):', 'poptheme-wassup'),
    //         )
    //     );
    // }

    // public function modalDialogClasses($classes)
    // {
    //     return array_merge(
    //         $classes,
    //         array(
    //             PoP_Module_Processor_LocationsMapBlocks::MODULE_BLOCK_STATICLOCATIONSMAP => 'modal-lg',
    //         )
    //     );
    // }
}

/**
 * Initialization
 */
new PoP_Locations_PageSectionHooks();
