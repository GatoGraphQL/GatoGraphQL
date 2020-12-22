<?php

class GD_Custom_Module_Processor_UserMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_SIDEBARMULTICOMPONENT_GENERICUSER = 'sidebarmulticomponent-genericuser';
    public const MODULE_SIDEBARMULTICOMPONENT_AVATAR = 'sidebarmulticomponent-avatar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_GENERICUSER],
            [self::class, self::MODULE_SIDEBARMULTICOMPONENT_AVATAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SIDEBARMULTICOMPONENT_GENERICUSER:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, GD_Custom_Module_Processor_UserWidgets::MODULE_WIDGETCOMPACT_GENERICUSERINFO];
                break;

            case self::MODULE_SIDEBARMULTICOMPONENT_AVATAR:
                if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_UserPhotoLayouts::class, PoP_Module_Processor_UserPhotoLayouts::MODULE_LAYOUT_AUTHOR_USERPHOTO];
                }
                $ret[] = [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::MODULE_USERSOCIALMEDIA];
                break;
        }

        return $ret;
    }
}



