<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_EM_BUTTON_GOOGLECALENDAR = 'em-button-googlecalendar';
    public final const COMPONENT_EM_BUTTON_ICAL = 'em-button-ical';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_BUTTON_GOOGLECALENDAR,
            self::COMPONENT_EM_BUTTON_ICAL,
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_BUTTON_GOOGLECALENDAR:
                return [GD_EM_Module_Processor_ButtonInners::class, GD_EM_Module_Processor_ButtonInners::COMPONENT_EM_BUTTONINNER_GOOGLECALENDAR];

            case self::COMPONENT_EM_BUTTON_ICAL:
                return [GD_EM_Module_Processor_ButtonInners::class, GD_EM_Module_Processor_ButtonInners::COMPONENT_EM_BUTTONINNER_ICAL];
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_BUTTON_GOOGLECALENDAR:
                return 'googleCalendarURL';

            case self::COMPONENT_EM_BUTTON_ICAL:
                return 'icalURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_BUTTON_GOOGLECALENDAR:
            case self::COMPONENT_EM_BUTTON_ICAL:
                return '_blank';
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_BUTTON_GOOGLECALENDAR:
                return TranslationAPIFacade::getInstance()->__('Google Calendar', 'em-popprocessors');

            case self::COMPONENT_EM_BUTTON_ICAL:
                return TranslationAPIFacade::getInstance()->__('iCal', 'em-popprocessors');
        }

        return parent::getTitle($component, $props);
    }
}


