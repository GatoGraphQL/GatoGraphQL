<?php

class GD_EM_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT = 'layout-postsidebarinner-vertical-event';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT = 'layout-postsidebarinner-vertical-pastevent';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT = 'layout-postsidebarinner-horizontal-event';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT = 'layout-postsidebarinner-horizontal-pastevent';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT = 'layout-postsidebarinner-compacthorizontal-event';
    public const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT = 'layout-postsidebarinner-compacthorizontal-pastevent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_EVENT)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_PASTEVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_PASTEVENT)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_EVENT)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_PASTEVENT)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_PASTEVENT:
                return 'col-xsm-4';
            
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_PASTEVENT:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



