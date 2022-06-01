<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_BUTTON_ADDONSPOSTEDIT = 'button-addonspostedit';
    public final const COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT = 'button-addonsormainpostedit';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTON_ADDONSPOSTEDIT],
            [self::class, self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT],
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_ADDONSPOSTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT],
            self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_ADDONSPOSTEDIT => 'editURL',
            self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT => 'editURL',
        );
        if ($field = $fields[$component->name] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_BUTTON_ADDONSPOSTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
            self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_ADDONSPOSTEDIT:
                return POP_TARGET_ADDONS;

            case self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTON_ADDONSPOSTEDIT:
            case self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


