<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_URE_BUTTON_EDITMEMBERSHIP = 'ure-button-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_BUTTON_EDITMEMBERSHIP],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ButtonInners::class, GD_URE_Module_Processor_ButtonInners::MODULE_URE_BUTTONINNER_EDITMEMBERSHIP];
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return 'editMembershipURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }
        
        return parent::getTitle($component, $props);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


