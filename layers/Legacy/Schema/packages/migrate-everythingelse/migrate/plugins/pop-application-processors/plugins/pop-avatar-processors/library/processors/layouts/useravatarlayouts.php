<?php

class PoP_Module_Processor_CustomUserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public final const COMPONENT_LAYOUT_USERAVATAR_40 = 'layout-useravatar-40';
    public final const COMPONENT_LAYOUT_USERAVATAR_40_RESPONSIVE = 'layout-useravatar-40-responsive';
    public final const COMPONENT_LAYOUT_USERAVATAR_120 = 'layout-useravatar-120';
    public final const COMPONENT_LAYOUT_USERAVATAR_120_RESPONSIVE = 'layout-useravatar-120-responsive';
    public final const COMPONENT_LAYOUT_USERAVATAR_150 = 'layout-useravatar-150';
    public final const COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE = 'layout-useravatar-150-responsive';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_USERAVATAR_40,
            self::COMPONENT_LAYOUT_USERAVATAR_40_RESPONSIVE,
            self::COMPONENT_LAYOUT_USERAVATAR_120,
            self::COMPONENT_LAYOUT_USERAVATAR_120_RESPONSIVE,
            self::COMPONENT_LAYOUT_USERAVATAR_150,
            self::COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE,
        );
    }

    public function getAvatarSize(\PoP\ComponentModel\Component\Component $component)
    {
        $avatars = array(
            self::COMPONENT_LAYOUT_USERAVATAR_40 => GD_AVATAR_SIZE_40,
            self::COMPONENT_LAYOUT_USERAVATAR_40_RESPONSIVE => GD_AVATAR_SIZE_40,
            self::COMPONENT_LAYOUT_USERAVATAR_120 => GD_AVATAR_SIZE_120,
            self::COMPONENT_LAYOUT_USERAVATAR_120_RESPONSIVE => GD_AVATAR_SIZE_120,
            self::COMPONENT_LAYOUT_USERAVATAR_150 => GD_AVATAR_SIZE_150,
            self::COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE => GD_AVATAR_SIZE_150,
        );

        if ($avatar = $avatars[$component->name] ?? null) {
            return $avatar;
        }

        return parent::getAvatarSize($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_USERAVATAR_40_RESPONSIVE:
            case self::COMPONENT_LAYOUT_USERAVATAR_120_RESPONSIVE:
            case self::COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE:
                $this->appendProp($component, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



