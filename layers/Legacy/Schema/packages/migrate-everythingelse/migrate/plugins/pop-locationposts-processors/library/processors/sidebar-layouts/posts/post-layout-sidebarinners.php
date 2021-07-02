<?php

class GD_Custom_EM_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST = 'layout-postsidebarinner-vertical-locationpost';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST = 'layout-postsidebarinner-horizontal-locationpost';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST = 'layout-postsidebarinner-compacthorizontal-locationpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
                $ret = array_merge(
                    $ret,
                    Custom_EM_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_LOCATIONPOST)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
                $ret = array_merge(
                    $ret,
                    Custom_EM_FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_LOCATIONPOST)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
                return 'col-xsm-4';
            
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



