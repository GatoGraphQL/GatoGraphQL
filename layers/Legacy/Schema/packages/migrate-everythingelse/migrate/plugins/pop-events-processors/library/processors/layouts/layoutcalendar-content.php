<?php

class PoP_Module_Processor_CalendarContentLayouts extends PoP_Module_Processor_CalendarContentLayoutsBase
{
    public final const COMPONENT_LAYOUTCALENDAR_CONTENT_POPOVER = 'em-layoutcalendar-content-popover';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTCALENDAR_CONTENT_POPOVER],
        );
    }
}



