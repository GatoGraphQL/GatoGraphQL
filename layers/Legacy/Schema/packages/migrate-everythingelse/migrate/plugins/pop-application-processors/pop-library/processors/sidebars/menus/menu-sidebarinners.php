<?php

class PoP_Module_Processor_CustomMenuSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_SIDEBARINNER_MENU_ABOUT = 'sidebarinner-menu-about';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARINNER_MENU_ABOUT],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_SIDEBARINNER_MENU_ABOUT:
                $ret[] = [GD_Custom_Module_Processor_MenuWidgets::class, GD_Custom_Module_Processor_MenuWidgets::MODULE_WIDGET_MENU_ABOUT];
                break;
        }
        
        return $ret;
    }
}



