<?php

class PoP_Module_Processor_CalendarControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CALENDARCONTROLBUTTONGROUP_CALENDAR = 'calendarcontrolbuttongroup-calendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CALENDARCONTROLBUTTONGROUP_CALENDAR],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CALENDARCONTROLBUTTONGROUP_CALENDAR:
                $ret[] = [PoP_Module_Processor_CalendarButtonControls::class, PoP_Module_Processor_CalendarButtonControls::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV];
                $ret[] = [PoP_Module_Processor_CalendarButtonControls::class, PoP_Module_Processor_CalendarButtonControls::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT];
                break;
        }
        
        return $ret;
    }
}


