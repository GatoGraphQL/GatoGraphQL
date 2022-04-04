<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_URE_BUTTON_EDITMEMBERSHIP = 'ure-button-editmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_BUTTON_EDITMEMBERSHIP],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ButtonInners::class, GD_URE_Module_Processor_ButtonInners::MODULE_URE_BUTTONINNER_EDITMEMBERSHIP];
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return 'editMembershipURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }
        
        return parent::getTitle($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


