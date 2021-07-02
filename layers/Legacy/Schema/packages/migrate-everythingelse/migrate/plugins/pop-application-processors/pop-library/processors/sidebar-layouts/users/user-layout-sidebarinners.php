<?php

class PoP_Module_Processor_CustomUserLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL = 'layout-usersidebarinner-vertical';
    public const MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL = 'layout-usersidebarinner-horizontal';
    public const MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL = 'layout-usersidebarinner-compacthorizontal';
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;

            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_GENERICUSER)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL:
                return 'col-xsm-4';

            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



