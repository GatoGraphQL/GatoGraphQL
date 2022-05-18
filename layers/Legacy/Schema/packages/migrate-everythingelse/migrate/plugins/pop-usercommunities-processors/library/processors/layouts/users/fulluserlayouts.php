<?php

class GD_UserCommunities_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const MODULE_LAYOUT_FULLUSER_COMMUNITY = 'layout-fulluser-community';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLUSER_COMMUNITY],
        );
    }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLUSER_COMMUNITY:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLUSER_COMMUNITY => [PoP_Module_Processor_CustomUserLayoutSidebars::class, PoP_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubmodule($component);
    }
}



