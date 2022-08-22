<?php

class GD_URE_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLUSER_ORGANIZATION = 'layout-fulluser-organization';
    public final const COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL = 'layout-fulluser-individual';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION,
            self::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL,
        );
    }

    public function getSidebarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION:
            case self::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION],
                    self::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL],
                );

                return $sidebars[$component->name];
        }

        return parent::getSidebarSubcomponent($component);
    }
}



