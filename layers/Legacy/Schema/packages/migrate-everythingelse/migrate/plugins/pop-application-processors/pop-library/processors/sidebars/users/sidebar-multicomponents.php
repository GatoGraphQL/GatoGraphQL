<?php

class GD_Custom_Module_Processor_UserMultipleSidebarComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_SIDEBARMULTICOMPONENT_GENERICUSER = 'sidebarmulticomponent-genericuser';
    public final const MODULE_SIDEBARMULTICOMPONENT_AVATAR = 'sidebarmulticomponent-avatar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_GENERICUSER],
            [self::class, self::COMPONENT_SIDEBARMULTICOMPONENT_AVATAR],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_SIDEBARMULTICOMPONENT_GENERICUSER:
                $ret[] = [PoP_Module_Processor_CustomPostWidgets::class, GD_Custom_Module_Processor_UserWidgets::COMPONENT_WIDGETCOMPACT_GENERICUSERINFO];
                break;

            case self::COMPONENT_SIDEBARMULTICOMPONENT_AVATAR:
                if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_UserPhotoLayouts::class, PoP_Module_Processor_UserPhotoLayouts::COMPONENT_LAYOUT_AUTHOR_USERPHOTO];
                }
                $ret[] = [PoP_Module_Processor_SocialMedia::class, PoP_Module_Processor_SocialMedia::COMPONENT_USERSOCIALMEDIA];
                break;
        }

        return $ret;
    }
}



