<?php

class PoP_Module_Processor_CustomUserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public final const MODULE_LAYOUT_USERAVATAR_40 = 'layout-useravatar-40';
    public final const MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE = 'layout-useravatar-40-responsive';
    public final const MODULE_LAYOUT_USERAVATAR_120 = 'layout-useravatar-120';
    public final const MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE = 'layout-useravatar-120-responsive';
    public final const MODULE_LAYOUT_USERAVATAR_150 = 'layout-useravatar-150';
    public final const MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE = 'layout-useravatar-150-responsive';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERAVATAR_40],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_120],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_150],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE],
        );
    }

    public function getAvatarSize(array $componentVariation)
    {
        $avatars = array(
            self::MODULE_LAYOUT_USERAVATAR_40 => GD_AVATAR_SIZE_40,
            self::MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE => GD_AVATAR_SIZE_40,
            self::MODULE_LAYOUT_USERAVATAR_120 => GD_AVATAR_SIZE_120,
            self::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE => GD_AVATAR_SIZE_120,
            self::MODULE_LAYOUT_USERAVATAR_150 => GD_AVATAR_SIZE_150,
            self::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE => GD_AVATAR_SIZE_150,
        );

        if ($avatar = $avatars[$componentVariation[1]] ?? null) {
            return $avatar;
        }

        return parent::getAvatarSize($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE:
            case self::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE:
            case self::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE:
                $this->appendProp($componentVariation, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



