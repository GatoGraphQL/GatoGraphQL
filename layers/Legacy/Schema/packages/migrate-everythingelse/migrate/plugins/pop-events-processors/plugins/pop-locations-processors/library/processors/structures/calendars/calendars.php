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

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_CALENDAR_EVENTSMAP => [PoP_Events_Locations_Module_Processor_CalendarInners::class, PoP_Events_Locations_Module_Processor_CalendarInners::MODULE_CALENDARINNER_EVENTSMAP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_CALENDAR_EVENTSMAP:
                $this->addJsmethod($ret, 'waypointsTheater');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CALENDAR_EVENTSMAP:
                // Make the offcanvas theater when the scroll reaches top of the page
                $this->appendProp($module, $props, 'class', 'waypoint');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'theater'
                    )
                );
                break;
        }

        switch ($module[1]) {
            case self::MODULE_CALENDAR_EVENTSMAP:
                // Make it activeItem: highlight on viewing the corresponding fullview
                $this->appendProp([GD_EM_Module_Processor_CustomPopoverLayouts::class, GD_EM_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_EVENT], $props, 'class', 'pop-openmapmarkers');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


