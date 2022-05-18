<?php

class PoP_Module_Processor_CustomPopoverLayouts extends PoP_Module_Processor_PopoverLayoutsBase
{
    public final const MODULE_LAYOUT_POPOVER_USER = 'layout-popover-user';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR = 'layout-popover-user-avatar';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR60 = 'layout-popover-user-avatar60';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR40 = 'layout-popover-user-avatar40';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR26 = 'layout-popover-user-avatar26';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POPOVER_USER],
            [self::class, self::MODULE_LAYOUT_POPOVER_USER_AVATAR],
            [self::class, self::MODULE_LAYOUT_POPOVER_USER_AVATAR60],
            [self::class, self::MODULE_LAYOUT_POPOVER_USER_AVATAR40],
            [self::class, self::MODULE_LAYOUT_POPOVER_USER_AVATAR26],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POPOVER_USER:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR60:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR40:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR26:
                return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_POPOVER];
        }

        return parent::getLayoutSubmodule($module);
    }

    public function getLayoutContentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POPOVER_USER:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
        }

        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($module[1]) {
                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR120];

                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR60:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR60];

                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR40:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];

                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR26:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR26];
            }
        } else {
            switch ($module[1]) {
                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR:
                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR60:
                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR40:
                case self::MODULE_LAYOUT_POPOVER_USER_AVATAR26:
                    return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
            }
        }

        return parent::getLayoutContentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POPOVER_USER:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR60:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR40:
            case self::MODULE_LAYOUT_POPOVER_USER_AVATAR26:
                // Use no Author popover
                $this->appendProp($module, $props, 'class', 'pop-elem');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



