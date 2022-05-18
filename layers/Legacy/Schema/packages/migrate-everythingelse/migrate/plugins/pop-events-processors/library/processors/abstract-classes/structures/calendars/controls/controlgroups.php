<?php

class PoP_Module_Processor_CalendarControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CALENDARCONTROLGROUP_CALENDAR = 'calendarcontrolgroup-calendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CALENDARCONTROLGROUP_CALENDAR],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CALENDARCONTROLGROUP_CALENDAR:
                $ret[] = [PoP_Module_Processor_CalendarControlButtonGroups::class, PoP_Module_Processor_CalendarControlButtonGroups::COMPONENT_CALENDARCONTROLBUTTONGROUP_CALENDAR];
                break;
        }

        return $ret;
    }
}


