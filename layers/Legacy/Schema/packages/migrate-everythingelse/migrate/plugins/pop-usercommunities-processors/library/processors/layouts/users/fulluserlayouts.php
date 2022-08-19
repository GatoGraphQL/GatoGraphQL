<?php

class GD_UserCommunities_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLUSER_COMMUNITY = 'layout-fulluser-community';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FULLUSER_COMMUNITY,
        );
    }

    public function getSidebarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FULLUSER_COMMUNITY:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLUSER_COMMUNITY => [PoP_Module_Processor_CustomUserLayoutSidebars::class, PoP_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL],
                );

                return $sidebars[$component->name];
        }

        return parent::getSidebarSubcomponent($component);
    }
}



