<?php

class PoP_Module_Processor_CustomTagLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_LAYOUT_TAGSIDEBARINNER_VERTICAL = 'layout-tagsidebarinner-vertical';
    public final const MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL = 'layout-tagsidebarinner-horizontal';
    public final const MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL = 'layout-tagsidebarinner-compacthorizontal';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_TAGSIDEBARINNER_VERTICAL],
            [self::class, self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL],
            [self::class, self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
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

    public function getWrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
                return 'col-xsm-4';

            case self::MODULE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



