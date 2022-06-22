<?php

class PoP_Module_Processor_NotificationActionIconLayouts extends PoP_Module_Processor_NotificationActionIconLayoutsBase
{
    public final const COMPONENT_LAYOUT_NOTIFICATIONICON = 'layout-notificationicon';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_NOTIFICATIONICON,
        );
    }
}


