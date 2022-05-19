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

    public function getButtoninnerSubcomponent(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_ADDONSPOSTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT],
            self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(array $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_ADDONSPOSTEDIT => 'editURL',
            self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT => 'editURL',
        );
        if ($field = $fields[$component[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }

    public function getTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_BUTTON_ADDONSPOSTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
            self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
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

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_BUTTON_ADDONSPOSTEDIT:
            case self::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }
}


