<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddLocations_PageSectionHooks
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
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomModalPageSections:get_props_block_initial:modals',
            $this->initModelProps(...),
            10,
            3
        );
    }

    // public function modalHeaderTitles($headerTitles)
    // {
    //     return array_merge(
    //         $headerTitles,
    //         array(
    //             GD_EM_Module_Processor_CreateLocationBlocks::COMPONENT_BLOCK_CREATELOCATION => sprintf(
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
    //             GD_EM_Module_Processor_CreateLocationBlocks::COMPONENT_BLOCK_CREATELOCATION => 'modal-lg',
    //         )
    //     );
    // }

    public function initModelProps(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component[1]) {
            case PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_MODALS:
                $processor->setProp([GD_EM_Module_Processor_CreateLocationBlocks::class, GD_EM_Module_Processor_CreateLocationBlocks::COMPONENT_BLOCK_CREATELOCATION], $props, 'title', '');
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_AddLocations_PageSectionHooks();
