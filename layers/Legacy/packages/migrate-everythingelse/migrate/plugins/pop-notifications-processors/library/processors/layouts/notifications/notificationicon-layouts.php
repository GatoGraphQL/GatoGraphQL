<?php

class PoP_Module_Processor_NotificationActionIconLayouts extends PoP_Module_Processor_NotificationActionIconLayoutsBase
{
    public const MODULE_LAYOUT_NOTIFICATIONICON = 'layout-notificationicon';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_NOTIFICATIONICON],
        );
    }
}


