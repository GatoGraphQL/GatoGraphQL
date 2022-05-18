<?php

class PoP_Module_Processor_CalendarControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CALENDARCONTROLGROUP_CALENDAR = 'calendarcontrolgroup-calendar';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDARCONTROLGROUP_CALENDAR],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_CALENDARCONTROLGROUP_CALENDAR:
                $ret[] = [PoP_Module_Processor_CalendarControlButtonGroups::class, PoP_Module_Processor_CalendarControlButtonGroups::MODULE_CALENDARCONTROLBUTTONGROUP_CALENDAR];
                break;
        }

        return $ret;
    }
}


