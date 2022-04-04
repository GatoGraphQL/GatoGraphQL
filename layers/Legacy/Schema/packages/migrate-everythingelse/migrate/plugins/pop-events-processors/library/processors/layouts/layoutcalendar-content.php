<?php

class PoP_Module_Processor_CalendarContentLayouts extends PoP_Module_Processor_CalendarContentLayoutsBase
{
    public final const MODULE_LAYOUTCALENDAR_CONTENT_POPOVER = 'em-layoutcalendar-content-popover';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTCALENDAR_CONTENT_POPOVER],
        );
    }
}



