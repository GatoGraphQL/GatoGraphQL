<?php

class PoP_Events_Locations_Module_Processor_CalendarInners extends PoP_Module_Processor_CalendarInnersBase
{
    public final const MODULE_CALENDARINNER_EVENTSMAP = 'calendarinner-eventsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDARINNER_EVENTSMAP],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CALENDARINNER_EVENTSMAP:
                $ret[] = [GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_EVENT];
                break;
        }
            
        return $ret;
    }
}


