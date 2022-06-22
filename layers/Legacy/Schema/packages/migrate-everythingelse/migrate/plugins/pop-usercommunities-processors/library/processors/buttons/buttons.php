<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_URE_BUTTON_EDITMEMBERSHIP = 'ure-button-editmembership';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_BUTTON_EDITMEMBERSHIP,
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_BUTTON_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ButtonInners::class, GD_URE_Module_Processor_ButtonInners::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP];
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_BUTTON_EDITMEMBERSHIP:
                return 'editMembershipURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_BUTTON_EDITMEMBERSHIP:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_BUTTON_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }
        
        return parent::getTitle($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_URE_BUTTON_EDITMEMBERSHIP:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


