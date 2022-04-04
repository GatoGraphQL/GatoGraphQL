<?php

class PoP_Module_Processor_CalendarControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CALENDARCONTROLGROUP_CALENDAR = 'calendarcontrolgroup-calendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDARCONTROLGROUP_CALENDAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CALENDARCONTROLGROUP_CALENDAR:
                $ret[] = [PoP_Module_Processor_CalendarControlButtonGroups::class, PoP_Module_Processor_CalendarControlButtonGroups::MODULE_CALENDARCONTROLBUTTONGROUP_CALENDAR];
                break;
        }

        return $ret;
    }
}


