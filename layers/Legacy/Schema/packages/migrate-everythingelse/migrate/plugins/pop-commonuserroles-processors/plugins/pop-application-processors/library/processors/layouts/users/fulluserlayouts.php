<?php

class GD_URE_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLUSER_ORGANIZATION = 'layout-fulluser-organization';
    public final const COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL = 'layout-fulluser-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION],
            [self::class, self::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL],
        );
    }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION:
            case self::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLUSER_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION],
                    self::COMPONENT_LAYOUT_FULLUSER_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::COMPONENT_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubmodule($component);
    }
}



