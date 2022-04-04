<?php

class PoP_Module_Processor_UserAvatarLayouts extends PoP_Module_Processor_UserAvatarLayoutsBase
{
    public final const MODULE_LAYOUT_USERAVATAR_60 = 'layout-useravatar-60';
    public final const MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE = 'layout-useravatar-60-responsive';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_USERAVATAR_60],
            [self::class, self::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE],
        );
    }

    public function getAvatarSize(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERAVATAR_60:
            case self::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE:
                return GD_AVATAR_SIZE_60;
        }

        return parent::getAvatarSize($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE:
                $this->appendProp($module, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



