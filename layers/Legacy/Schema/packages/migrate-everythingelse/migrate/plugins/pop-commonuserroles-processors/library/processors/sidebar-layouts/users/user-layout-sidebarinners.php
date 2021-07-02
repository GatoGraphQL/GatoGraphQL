<?php

class GD_URE_Module_Processor_CustomUserLayoutSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public const MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION = 'layout-usersidebarinner-vertical-organization';
    public const MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL = 'layout-usersidebarinner-vertical-individual';
    public const MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION = 'layout-usersidebarinner-horizontal-organization';
    public const MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL = 'layout-usersidebarinner-horizontal-individual';
    public const MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION = 'layout-usersidebarinner-compacthorizontal-organization';
    public const MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL = 'layout-usersidebarinner-compacthorizontal-individual';
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION],
            [self::class, self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_ORGANIZATION:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_ORGANIZATION)
                );
                break;

            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_VERTICAL_INDIVIDUAL:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_INDIVIDUAL)
                );
                break;

            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_ORGANIZATION)
                );
                break;

            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_COMPACTSIDEBARSECTION_INDIVIDUAL)
                );
                break;
        }
        
        return $ret;
    }

    public function getWrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL:
                return 'row';
        }
    
        return parent::getWrapperClass($module);
    }
    
    public function getWidgetwrapperClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_HORIZONTAL_INDIVIDUAL:
                return 'col-xsm-4';

            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_ORGANIZATION:
            case self::MODULE_LAYOUT_USERSIDEBARINNER_COMPACTHORIZONTAL_INDIVIDUAL:
                return 'col-xsm-6';
        }
    
        return parent::getWidgetwrapperClass($module);
    }
}



