<?php

class PoP_Module_Processor_UserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public final const MODULE_LAYOUT_USERAVATAR_60 = 'layout-useravatar-60';
    public final const MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE = 'layout-useravatar-60-responsive';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERAVATAR_60],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE],
        );
    }

    public function getAvatarSize(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_USERAVATAR_60:
            case self::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE:
                return GD_AVATAR_SIZE_60;
        }

        return parent::getAvatarSize($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE:
                $this->appendProp($componentVariation, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



