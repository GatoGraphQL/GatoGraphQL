<?php

class GD_UserCommunities_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const MODULE_LAYOUT_FULLUSER_COMMUNITY = 'layout-fulluser-community';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLUSER_COMMUNITY],
        );
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLUSER_COMMUNITY:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLUSER_COMMUNITY => [PoP_Module_Processor_CustomUserLayoutSidebars::class, PoP_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL],
                );

                return $sidebars[$module[1]];
        }

        return parent::getSidebarSubmodule($module);
    }
}



