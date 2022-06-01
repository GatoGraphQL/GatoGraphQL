<?php

class PoP_Module_Processor_NotificationTimeLayouts extends PoP_Module_Processor_NotificationTimeLayoutsBase
{
    public final const COMPONENT_LAYOUT_NOTIFICATIONTIME = 'layout-notificationtime';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_NOTIFICATIONTIME,
        );
    }
}


