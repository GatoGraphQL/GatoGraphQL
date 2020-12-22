<?php

class PoP_Module_Processor_CustomUserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public const MODULE_LAYOUT_USERAVATAR_40 = 'layout-useravatar-40';
    public const MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE = 'layout-useravatar-40-responsive';
    public const MODULE_LAYOUT_USERAVATAR_120 = 'layout-useravatar-120';
    public const MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE = 'layout-useravatar-120-responsive';
    public const MODULE_LAYOUT_USERAVATAR_150 = 'layout-useravatar-150';
    public const MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE = 'layout-useravatar-150-responsive';

    public function getModulesToProcess(): array
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

    public function getAvatarSize(array $module)
    {
        $avatars = array(
            self::MODULE_LAYOUT_USERAVATAR_40 => GD_AVATAR_SIZE_40,
            self::MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE => GD_AVATAR_SIZE_40,
            self::MODULE_LAYOUT_USERAVATAR_120 => GD_AVATAR_SIZE_120,
            self::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE => GD_AVATAR_SIZE_120,
            self::MODULE_LAYOUT_USERAVATAR_150 => GD_AVATAR_SIZE_150,
            self::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE => GD_AVATAR_SIZE_150,
        );

        if ($avatar = $avatars[$module[1]]) {
            return $avatar;
        }

        return parent::getAvatarSize($module);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERAVATAR_40_RESPONSIVE:
            case self::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE:
            case self::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE:
                $this->appendProp($module, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



