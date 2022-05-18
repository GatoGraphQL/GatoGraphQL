<?php

class PoP_Module_Processor_NotificationActionIconLayouts extends PoP_Module_Processor_NotificationActionIconLayoutsBase
{
    public final const MODULE_LAYOUT_NOTIFICATIONICON = 'layout-notificationicon';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_NOTIFICATIONICON],
        );
    }
}


