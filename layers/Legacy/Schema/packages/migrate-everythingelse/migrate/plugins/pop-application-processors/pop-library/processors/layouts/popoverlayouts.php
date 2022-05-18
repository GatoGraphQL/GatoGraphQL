<?php

class PoP_Module_Processor_CustomPopoverLayouts extends PoP_Module_Processor_PopoverLayoutsBase
{
    public final const MODULE_LAYOUT_POPOVER_USER = 'layout-popover-user';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR = 'layout-popover-user-avatar';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR60 = 'layout-popover-user-avatar60';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR40 = 'layout-popover-user-avatar40';
    public final const MODULE_LAYOUT_POPOVER_USER_AVATAR26 = 'layout-popover-user-avatar26';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POPOVER_USER],
            [self::class, self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR],
            [self::class, self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR60],
            [self::class, self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40],
            [self::class, self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR26],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POPOVER_USER:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR60:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR26:
                return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_POPOVER];
        }

        return parent::getLayoutSubmodule($component);
    }

    public function getLayoutContentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POPOVER_USER:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
        }

        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component[1]) {
                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR120];

                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR60:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR60];

                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR];

                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR26:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR26];
            }
        } else {
            switch ($component[1]) {
                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR:
                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR60:
                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40:
                case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR26:
                    return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
            }
        }

        return parent::getLayoutContentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POPOVER_USER:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR60:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR40:
            case self::COMPONENT_LAYOUT_POPOVER_USER_AVATAR26:
                // Use no Author popover
                $this->appendProp($component, $props, 'class', 'pop-elem');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



