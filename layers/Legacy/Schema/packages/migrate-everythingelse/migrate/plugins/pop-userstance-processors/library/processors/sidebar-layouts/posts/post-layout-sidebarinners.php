<?php

class UserStance_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_STANCE = 'layout-postsidebarinner-vertical-stance';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE = 'layout-postsidebarinner-horizontal-stance';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE = 'layout-postsidebarinner-compacthorizontal-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_STANCE],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_STANCE:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_STANCE)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE:
                $ret = array_merge(
                    $ret,
                    UserStance_FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_STANCE)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STANCE:
                return 'col-xsm-4';
            
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STANCE:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



