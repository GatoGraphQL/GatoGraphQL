<?php

class PoP_Module_Processor_CustomTagLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_TAGSIDEBARINNER_VERTICAL = 'layout-tagsidebarinner-vertical';
    public final const MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL = 'layout-tagsidebarinner-horizontal';
    public final const MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL = 'layout-tagsidebarinner-compacthorizontal';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_TAGSIDEBARINNER_VERTICAL],
            [self::class, self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_VERTICAL:
                $ret = array_merge(
                    $ret,
                    FullTagSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_TAG)
                );
                break;

            case self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                $ret = array_merge(
                    $ret,
                    FullTagSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_TAG)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
                return 'col-xsm-4';

            case self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



