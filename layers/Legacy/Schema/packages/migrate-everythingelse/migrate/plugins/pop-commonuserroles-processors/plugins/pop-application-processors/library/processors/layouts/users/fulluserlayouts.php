<?php

class GD_URE_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const MODULE_LAYOUT_FULLUSER_ORGANIZATION = 'layout-fulluser-organization';
    public final const MODULE_LAYOUT_FULLUSER_INDIVIDUAL = 'layout-fulluser-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLUSER_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_FULLUSER_INDIVIDUAL],
        );
    }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_FULLUSER_ORGANIZATION:
            case self::MODULE_LAYOUT_FULLUSER_INDIVIDUAL:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLUSER_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION],
                    self::MODULE_LAYOUT_FULLUSER_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubmodule($component);
    }
}



