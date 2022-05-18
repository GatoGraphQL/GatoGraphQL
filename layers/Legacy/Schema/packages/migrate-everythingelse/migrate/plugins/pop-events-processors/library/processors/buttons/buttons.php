<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_EM_BUTTON_GOOGLECALENDAR = 'em-button-googlecalendar';
    public final const MODULE_EM_BUTTON_ICAL = 'em-button-ical';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_BUTTON_GOOGLECALENDAR],
            [self::class, self::MODULE_EM_BUTTON_ICAL],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_EM_BUTTON_GOOGLECALENDAR:
                return [GD_EM_Module_Processor_ButtonInners::class, GD_EM_Module_Processor_ButtonInners::MODULE_EM_BUTTONINNER_GOOGLECALENDAR];

            case self::MODULE_EM_BUTTON_ICAL:
                return [GD_EM_Module_Processor_ButtonInners::class, GD_EM_Module_Processor_ButtonInners::MODULE_EM_BUTTONINNER_ICAL];
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_EM_BUTTON_GOOGLECALENDAR:
                return 'googleCalendarURL';

            case self::MODULE_EM_BUTTON_ICAL:
                return 'icalURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_EM_BUTTON_GOOGLECALENDAR:
            case self::MODULE_EM_BUTTON_ICAL:
                return '_blank';
        }

        return parent::getLinktarget($component, $props);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_EM_BUTTON_GOOGLECALENDAR:
                return TranslationAPIFacade::getInstance()->__('Google Calendar', 'em-popprocessors');

            case self::MODULE_EM_BUTTON_ICAL:
                return TranslationAPIFacade::getInstance()->__('iCal', 'em-popprocessors');
        }

        return parent::getTitle($component, $props);
    }
}


