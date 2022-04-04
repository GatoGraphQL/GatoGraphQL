<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Locations_PageSectionHooks
{
    public function __construct()
    {
        // \PoP\Root\App::addFilter(
        //     'PoP_Module_Processor_CustomModalPageSections:getDialogClasses:modals',
        //     $this->modalDialogClasses(...)
        // );
        // \PoP\Root\App::addFilter(
        //     'PoP_Module_Processor_CustomModalPageSections:getHeaderTitles:modals',
        //     $this->modalHeaderTitles(...)
        // );
        // \PoP\Root\App::addFilter(
        //     'PoP_Module_Processor_CustomModalPageSections:getBodyClasses:modals',
        //     $this->modalBodyClasses(...)
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
