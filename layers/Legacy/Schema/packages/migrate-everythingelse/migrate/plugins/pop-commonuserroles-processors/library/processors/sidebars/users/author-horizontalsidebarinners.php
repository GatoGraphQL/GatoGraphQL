<?php

class GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION = 'horizontal-sidebarinner-author-organization';
    public final const MODULE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL = 'horizontal-sidebarinner-author-individual';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION],
            [self::class, self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_ORGANIZATION)
                );
                break;

            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_INDIVIDUAL)
                );
                break;
        }

        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
            case self::MODULE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



