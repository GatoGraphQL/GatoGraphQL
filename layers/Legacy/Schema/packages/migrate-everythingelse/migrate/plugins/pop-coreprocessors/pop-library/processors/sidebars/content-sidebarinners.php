<?php

class PoP_Module_Processor_ContentSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_SIDEBARINNER_CONTENT_HORIZONTAL = 'contentsidebarinner-horizontal';
    public final const MODULE_SIDEBARINNER_CONTENT_VERTICAL = 'contentsidebarinner-vertical';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARINNER_CONTENT_HORIZONTAL],
            [self::class, self::MODULE_SIDEBARINNER_CONTENT_VERTICAL],
        );
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SIDEBARINNER_CONTENT_HORIZONTAL:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }

    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SIDEBARINNER_CONTENT_HORIZONTAL:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



