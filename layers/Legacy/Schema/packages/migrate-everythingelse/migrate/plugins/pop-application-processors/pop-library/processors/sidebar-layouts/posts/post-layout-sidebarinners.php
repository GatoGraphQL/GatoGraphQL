<?php

class PoP_Module_Processor_CustomPostLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL = 'layout-postsidebarinner-vertical';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT = 'layout-postsidebarinner-vertical-highlight';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_POST = 'layout-postsidebarinner-vertical-post';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL = 'layout-postsidebarinner-horizontal';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT = 'layout-postsidebarinner-horizontal-highlight';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST = 'layout-postsidebarinner-horizontal-post';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL = 'layout-postsidebarinner-compacthorizontal';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT = 'layout-postsidebarinner-compacthorizontal-highlight';
    public final const MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST = 'layout-postsidebarinner-compacthorizontal-post';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_POST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERIC)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_HIGHLIGHT)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_VERTICAL_POST:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_POST)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_GENERIC)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_HIGHLIGHT)
                );
                break;

            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_POST)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_POST:
                return 'col-xsm-4';
            
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
            case self::MODULE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



