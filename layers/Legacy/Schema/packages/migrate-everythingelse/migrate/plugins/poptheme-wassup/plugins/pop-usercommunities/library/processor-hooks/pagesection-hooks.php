<?php
use PoP\Engine\Route\RouteUtils;

class PoPTheme_Wassup_UserCommunities_PageSectionHooks
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

    // public function modalHeaderTitles($headerTitles)
    // {
    //     $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    //     return array_merge(
    //         $headerTitles,
    //         array(
    //             GD_URE_Module_Processor_ProfileBlocks::MODULE_BLOCK_INVITENEWMEMBERS => RouteUtils::getRouteTitle(POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS),
    //         )
    //     );
    // }

    public function initModelProps(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_Module_Processor_PageSections::MODULE_PAGESECTION_MODALS:
                $processor->setProp([GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::MODULE_BLOCK_INVITENEWMEMBERS], $props, 'title', '');
                break;
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_UserCommunities_PageSectionHooks();
