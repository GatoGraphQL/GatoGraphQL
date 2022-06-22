<?php

class PoP_Module_Processor_CustomMenuSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_SIDEBARINNER_MENU_ABOUT = 'sidebarinner-menu-about';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SIDEBARINNER_MENU_ABOUT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_SIDEBARINNER_MENU_ABOUT:
                $ret[] = [GD_Custom_Module_Processor_MenuWidgets::class, GD_Custom_Module_Processor_MenuWidgets::COMPONENT_WIDGET_MENU_ABOUT];
                break;
        }
        
        return $ret;
    }
}



