<?php

class PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LINK = 'layout-postsidebarinner-vertical-link';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK = 'layout-postsidebarinner-horizontal-link';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK = 'layout-postsidebarinner-compacthorizontal-link';

    public function getModulesToProcess(): array
    {
        return array(
            [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LINK],
            [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK],
            [PoP_Module_Processor_CustomPostLayoutSidebarInners::class, PoP_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LINK:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_POSTLINK)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_POSTLINK)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK:
                return 'col-xsm-4';
            
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



