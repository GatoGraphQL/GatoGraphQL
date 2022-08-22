<?php

class PoP_Module_Processor_UserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public final const COMPONENT_LAYOUT_USERAVATAR_60 = 'layout-useravatar-60';
    public final const COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE = 'layout-useravatar-60-responsive';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_USERAVATAR_60,
            self::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE,
        );
    }

    public function getAvatarSize(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_USERAVATAR_60:
            case self::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE:
                return GD_AVATAR_SIZE_60;
        }

        return parent::getAvatarSize($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE:
                $this->appendProp($component, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



