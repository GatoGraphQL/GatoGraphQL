<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_URE_BUTTON_EDITMEMBERSHIP = 'ure-button-editmembership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_BUTTON_EDITMEMBERSHIP],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ButtonInners::class, GD_URE_Module_Processor_ButtonInners::MODULE_URE_BUTTONINNER_EDITMEMBERSHIP];
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return 'editMembershipURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }
        
        return parent::getTitle($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_BUTTON_EDITMEMBERSHIP:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


