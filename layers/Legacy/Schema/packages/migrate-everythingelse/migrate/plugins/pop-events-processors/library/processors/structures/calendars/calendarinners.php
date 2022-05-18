<?php

class GD_EM_Module_Processor_CalendarInners extends PoP_Module_Processor_CalendarInnersBase
{
    public final const MODULE_CALENDARINNER_EVENTS_NAVIGATOR = 'calendarinner-events-navigator';
    public final const MODULE_CALENDARINNER_EVENTS_ADDONS = 'calendarinner-events-addons';
    public final const MODULE_CALENDARINNER_EVENTS_MAIN = 'calendarinner-events-main';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDARINNER_EVENTS_NAVIGATOR],
            [self::class, self::MODULE_CALENDARINNER_EVENTS_ADDONS],
            [self::class, self::MODULE_CALENDARINNER_EVENTS_MAIN],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CALENDARINNER_EVENTS_NAVIGATOR:
            case self::MODULE_CALENDARINNER_EVENTS_ADDONS:
            case self::MODULE_CALENDARINNER_EVENTS_MAIN:
                $ret[] = [GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_EVENT];
                break;
        }
            
        return $ret;
    }
}


