<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Add a module to include Community members in the filter
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_UserCardLayoutsBase:getAdditionalSubmodules', 'gdUreUsercardlayoutAddcommunitytemplate', 10, 2);
function gdUreUsercardlayoutAddcommunitytemplate($extra_modules, array $module)
{
    if ($module == [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::MODULE_LAYOUTUSER_FILTERCARD]) {
        $extra_modules[] = [PoP_UserCommunities_Module_Processor_FormInputInputWrappers::class, PoP_UserCommunities_Module_Processor_FormInputInputWrappers::MODULE_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY];
    }
    return $extra_modules;
}
