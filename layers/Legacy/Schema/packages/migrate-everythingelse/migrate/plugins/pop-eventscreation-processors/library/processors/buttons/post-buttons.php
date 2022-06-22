<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_Buttons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const COMPONENT_BUTTON_EVENT_CREATE = 'postbutton-event-create';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTON_EVENT_CREATE,
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_EVENT_CREATE => [GD_Custom_EM_Module_Processor_ButtonInners::class, GD_Custom_EM_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_EVENT_CREATE],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getTargetDynamicallyRenderedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_EVENT_CREATE:
                return array(
                    [PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_POST],
                );
        }

        return parent::getTargetDynamicallyRenderedSubcomponents($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_EVENT_CREATE:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_BUTTON_EVENT_CREATE => TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_EVENT_CREATE => 'addEventURL',
        );
        if ($field = $fields[$component->name] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }
}



