<?php

class PoP_Module_Processor_NotificationTimeLayouts extends PoP_Module_Processor_NotificationTimeLayoutsBase
{
    public final const MODULE_LAYOUT_NOTIFICATIONTIME = 'layout-notificationtime';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_NOTIFICATIONTIME],
        );
    }
}


