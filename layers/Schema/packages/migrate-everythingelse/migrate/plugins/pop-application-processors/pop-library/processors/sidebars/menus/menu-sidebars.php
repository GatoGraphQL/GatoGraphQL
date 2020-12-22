<?php

class PoP_Module_Processor_CustomMenuSidebars extends PoP_Module_Processor_SidebarsBase
{
    public const MODULE_SIDEBAR_MENU_ABOUT = 'sidebar-menu-about';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBAR_MENU_ABOUT],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $sidebarinners = array(
            self::MODULE_SIDEBAR_MENU_ABOUT => [PoP_Module_Processor_CustomMenuSidebarInners::class, PoP_Module_Processor_CustomMenuSidebarInners::MODULE_SIDEBARINNER_MENU_ABOUT],
        );

        if ($inner = $sidebarinners[$module[1]]) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



