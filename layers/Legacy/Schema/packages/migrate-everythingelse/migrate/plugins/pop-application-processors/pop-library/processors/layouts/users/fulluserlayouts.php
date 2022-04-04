<?php

class PoP_Module_Processor_CustomFullUserLayouts extends PoP_Module_Processor_CustomFullUserLayoutsBase
{
    public final const MODULE_LAYOUT_FULLUSER = 'layout-fulluser';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLUSER],
        );
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLUSER:
                return [PoP_Module_Processor_CustomUserLayoutSidebars::class, PoP_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL];
        }

        return parent::getSidebarSubmodule($module);
    }
}


