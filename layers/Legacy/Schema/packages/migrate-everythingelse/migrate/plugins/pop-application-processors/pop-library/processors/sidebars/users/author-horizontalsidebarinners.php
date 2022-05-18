<?php

class PoP_Module_Processor_CustomHorizontalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC = 'horizontal-sidebarinner-author-generic';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;
        }

        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



