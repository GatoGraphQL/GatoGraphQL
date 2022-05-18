<?php
use PoP\Engine\Route\RouteUtils;

class PoPTheme_Wassup_PoPCore_PageSectionHooks
{
    public function __construct()
    {
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

    public function initModelProps(array $componentVariation, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS:
                $processor->setProp([PoP_UserPlatform_Module_Processor_Blocks::class, PoP_UserPlatform_Module_Processor_Blocks::MODULE_BLOCK_INVITENEWUSERS], $props, 'title', '');
                break;
        }
    }

    // public function modalHeaderTitles($headerTitles)
    // {
    //     return array_merge(
    //         $headerTitles,
    //         array(
    //             PoP_UserPlatform_Module_Processor_Blocks::MODULE_BLOCK_INVITENEWUSERS => RouteUtils::getRouteTitle(POP_USERPLATFORM_ROUTE_INVITENEWUSERS),
    //         )
    //     );
    // }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_PoPCore_PageSectionHooks();
