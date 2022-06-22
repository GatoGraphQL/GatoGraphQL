<?php

// Add a module to include Community members in the filter
\PoP\Root\App::addFilter('PoP_Module_Processor_UserCardLayoutsBase:getAdditionalSubcomponents', 'gdUreUsercardlayoutAddcommunitytemplate', 10, 2);
function gdUreUsercardlayoutAddcommunitytemplate($extra_components, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_FILTERCARD]) {
        $extra_components[] = [PoP_UserCommunities_Module_Processor_FormInputInputWrappers::class, PoP_UserCommunities_Module_Processor_FormInputInputWrappers::COMPONENT_FILTERINPUTWRAPPER_FILTERBYCOMMUNITY];
    }
    return $extra_components;
}
