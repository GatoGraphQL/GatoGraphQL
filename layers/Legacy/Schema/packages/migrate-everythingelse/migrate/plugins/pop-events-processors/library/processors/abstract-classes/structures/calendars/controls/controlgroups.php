<?php

class PoP_Module_Processor_CalendarControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CALENDARCONTROLGROUP_CALENDAR = 'calendarcontrolgroup-calendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDARCONTROLGROUP_CALENDAR],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_CALENDARCONTROLGROUP_CALENDAR:
                $ret[] = [PoP_Module_Processor_CalendarControlButtonGroups::class, PoP_Module_Processor_CalendarControlButtonGroups::MODULE_CALENDARCONTROLBUTTONGROUP_CALENDAR];
                break;
        }

        return $ret;
    }
}


