<?php

class GD_EM_Module_Processor_CalendarInners extends PoP_Module_Processor_CalendarInnersBase
{
    public final const COMPONENT_CALENDARINNER_EVENTS_NAVIGATOR = 'calendarinner-events-navigator';
    public final const COMPONENT_CALENDARINNER_EVENTS_ADDONS = 'calendarinner-events-addons';
    public final const COMPONENT_CALENDARINNER_EVENTS_MAIN = 'calendarinner-events-main';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CALENDARINNER_EVENTS_NAVIGATOR],
            [self::class, self::COMPONENT_CALENDARINNER_EVENTS_ADDONS],
            [self::class, self::COMPONENT_CALENDARINNER_EVENTS_MAIN],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CALENDARINNER_EVENTS_NAVIGATOR:
            case self::COMPONENT_CALENDARINNER_EVENTS_ADDONS:
            case self::COMPONENT_CALENDARINNER_EVENTS_MAIN:
                $ret[] = [GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_EVENT];
                break;
        }
            
        return $ret;
    }
}


