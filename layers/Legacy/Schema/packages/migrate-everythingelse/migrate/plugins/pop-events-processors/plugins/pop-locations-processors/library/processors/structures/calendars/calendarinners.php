<?php

class PoP_Events_Locations_Module_Processor_CalendarInners extends PoP_Module_Processor_CalendarInnersBase
{
    public final const COMPONENT_CALENDARINNER_EVENTSMAP = 'calendarinner-eventsmap';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CALENDARINNER_EVENTSMAP,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CALENDARINNER_EVENTSMAP:
                $ret[] = [GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_EVENT];
                break;
        }
            
        return $ret;
    }
}


