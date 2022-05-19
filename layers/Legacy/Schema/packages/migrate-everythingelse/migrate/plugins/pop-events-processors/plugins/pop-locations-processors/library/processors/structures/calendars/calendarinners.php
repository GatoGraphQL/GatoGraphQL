<?php

class PoP_Events_Locations_Module_Processor_CalendarInners extends PoP_Module_Processor_CalendarInnersBase
{
    public final const COMPONENT_CALENDARINNER_EVENTSMAP = 'calendarinner-eventsmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CALENDARINNER_EVENTSMAP],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CALENDARINNER_EVENTSMAP:
                $ret[] = [GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_EVENT];
                break;
        }
            
        return $ret;
    }
}


