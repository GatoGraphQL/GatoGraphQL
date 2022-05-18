<?php

class PoP_Module_Processor_UserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public final const MODULE_LAYOUT_USERAVATAR_60 = 'layout-useravatar-60';
    public final const MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE = 'layout-useravatar-60-responsive';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_USERAVATAR_60],
            [self::class, self::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE],
        );
    }

    public function getAvatarSize(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERAVATAR_60:
            case self::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE:
                return GD_AVATAR_SIZE_60;
        }

        return parent::getAvatarSize($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE:
                $this->appendProp($component, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



