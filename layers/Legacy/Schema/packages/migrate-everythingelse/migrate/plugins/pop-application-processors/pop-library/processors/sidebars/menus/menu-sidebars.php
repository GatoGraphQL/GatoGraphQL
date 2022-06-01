<?php

class PoP_Module_Processor_CustomMenuSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_SIDEBAR_MENU_ABOUT = 'sidebar-menu-about';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SIDEBAR_MENU_ABOUT,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_SIDEBAR_MENU_ABOUT => [PoP_Module_Processor_CustomMenuSidebarInners::class, PoP_Module_Processor_CustomMenuSidebarInners::COMPONENT_SIDEBARINNER_MENU_ABOUT],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



