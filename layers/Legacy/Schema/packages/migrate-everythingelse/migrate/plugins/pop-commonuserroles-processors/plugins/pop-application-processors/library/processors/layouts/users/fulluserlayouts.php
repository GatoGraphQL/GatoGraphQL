<?php

class GD_URE_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public const MODULE_LAYOUT_FULLUSER_ORGANIZATION = 'layout-fulluser-organization';
    public const MODULE_LAYOUT_FULLUSER_INDIVIDUAL = 'layout-fulluser-individual';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLUSER_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_FULLUSER_INDIVIDUAL],
        );
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLUSER_ORGANIZATION:
            case self::MODULE_LAYOUT_FULLUSER_INDIVIDUAL:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLUSER_ORGANIZATION => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION],
                    self::MODULE_LAYOUT_FULLUSER_INDIVIDUAL => [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL],
                );

                return $sidebars[$module[1]];
        }

        return parent::getSidebarSubmodule($module);
    }
}



