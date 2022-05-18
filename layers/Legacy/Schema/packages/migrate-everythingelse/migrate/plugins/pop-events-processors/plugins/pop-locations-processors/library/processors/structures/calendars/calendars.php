<?php

class PoP_Events_Locations_Module_Processor_Calendars extends PoP_Module_Processor_CalendarsBase
{
    public final const MODULE_CALENDAR_EVENTSMAP = 'calendar-eventsmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDAR_EVENTSMAP],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_CALENDAR_EVENTSMAP => [PoP_Events_Locations_Module_Processor_CalendarInners::class, PoP_Events_Locations_Module_Processor_CalendarInners::MODULE_CALENDARINNER_EVENTSMAP],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_CALENDAR_EVENTSMAP:
                $this->addJsmethod($ret, 'waypointsTheater');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CALENDAR_EVENTSMAP:
                // Make the offcanvas theater when the scroll reaches top of the page
                $this->appendProp($componentVariation, $props, 'class', 'waypoint');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'theater'
                    )
                );
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_CALENDAR_EVENTSMAP:
                // Make it activeItem: highlight on viewing the corresponding fullview
                $this->appendProp([GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_EVENT], $props, 'class', 'pop-openmapmarkers');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


