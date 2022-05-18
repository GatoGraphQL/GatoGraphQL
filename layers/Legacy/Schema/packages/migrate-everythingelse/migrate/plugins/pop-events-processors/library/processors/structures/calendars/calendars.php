<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_Calendars extends PoP_Module_Processor_CalendarsBase
{
    public final const MODULE_CALENDAR_EVENTS_NAVIGATOR = 'calendar-events-navigator';
    public final const MODULE_CALENDAR_EVENTS_ADDONS = 'calendar-events-addons';
    public final const MODULE_CALENDAR_EVENTS_MAIN = 'calendar-events-main';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CALENDAR_EVENTS_NAVIGATOR],
            [self::class, self::COMPONENT_CALENDAR_EVENTS_ADDONS],
            [self::class, self::COMPONENT_CALENDAR_EVENTS_MAIN],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_CALENDAR_EVENTS_NAVIGATOR => [GD_EM_Module_Processor_CalendarInners::class, GD_EM_Module_Processor_CalendarInners::COMPONENT_CALENDARINNER_EVENTS_NAVIGATOR],
            self::COMPONENT_CALENDAR_EVENTS_ADDONS => [GD_EM_Module_Processor_CalendarInners::class, GD_EM_Module_Processor_CalendarInners::COMPONENT_CALENDARINNER_EVENTS_ADDONS],
            self::COMPONENT_CALENDAR_EVENTS_MAIN => [GD_EM_Module_Processor_CalendarInners::class, GD_EM_Module_Processor_CalendarInners::COMPONENT_CALENDARINNER_EVENTS_MAIN],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function getOptions(array $component, array &$props)
    {
        $ret = parent::getOptions($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_CALENDAR_EVENTS_NAVIGATOR:
            case self::COMPONENT_CALENDAR_EVENTS_ADDONS:
                // Comment Leo 12/08/2016: if adding directly the first letter, then it can't be translated, so use the full name and get the first letter for each day
                // $ret['dayNamesShort'] = array('S', 'M', 'T', 'W', 'T', 'F', 'S');

                // Comment Leo 23/08/2016: There was a bug, in which the website crashed in Chinese, and it was from using substr on Chinese characters. So use mb_substr which has support for UTF-8
                $ret['dayNamesShort'] = array(
                    mb_substr(TranslationAPIFacade::getInstance()->__('Sunday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                    mb_substr(TranslationAPIFacade::getInstance()->__('Monday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                    mb_substr(TranslationAPIFacade::getInstance()->__('Tuesday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                    mb_substr(TranslationAPIFacade::getInstance()->__('Wednesday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                    mb_substr(TranslationAPIFacade::getInstance()->__('Thursday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                    mb_substr(TranslationAPIFacade::getInstance()->__('Friday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                    mb_substr(TranslationAPIFacade::getInstance()->__('Saturday', 'poptheme-wassup'), 0, 1, 'UTF-8'),
                );
                $ret['titleFormat'] = 'MMM YYYY';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CALENDAR_EVENTS_NAVIGATOR:
            case self::COMPONENT_CALENDAR_EVENTS_ADDONS:
                // Do not show the Title in the Calendar Navigator, no space
                $this->setProp([PoP_Module_Processor_CalendarContentLayouts::class, PoP_Module_Processor_CalendarContentLayouts::COMPONENT_LAYOUTCALENDAR_CONTENT_POPOVER], $props, 'show-title', false);
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_CALENDAR_EVENTS_MAIN:
                // Make it activeItem: highlight on viewing the corresponding fullview
                $this->appendProp([GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_EVENT], $props, 'class', 'pop-openmapmarkers');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


