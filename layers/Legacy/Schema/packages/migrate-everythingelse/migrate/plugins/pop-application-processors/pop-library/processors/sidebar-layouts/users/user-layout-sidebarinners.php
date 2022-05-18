<?php

class PoP_Module_Processor_CustomUserLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL = 'layout-usersidebarinner-vertical';
    public final const MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL = 'layout-usersidebarinner-horizontal';
    public final const MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL = 'layout-usersidebarinner-compacthorizontal';
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_USERSIDEBARINNER_VERTICAL],
            [self::class, self::COMPONENT_LAYOUT_USERSIDEBARINNER_HORIZONTAL],
            [self::class, self::COMPONENT_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_VERTICAL:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;

            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_GENERICUSER)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_HORIZONTAL:
            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_HORIZONTAL:
                return 'col-xsm-4';

            case self::COMPONENT_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



