<?php

class PoP_Module_Processor_Alerts extends PoP_Module_Processor_AlertsBase
{
    public final const MODULE_ALERT_STICKY = 'alert-sticky';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ALERT_STICKY],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_ALERT_STICKY:
                return [PoP_Module_Processor_AnnouncementSpeechBubbles::class, PoP_Module_Processor_AnnouncementSpeechBubbles::MODULE_ANNOUNCEMENTSPEECHBUBBLE_STICKY];
        }

        return parent::getLayoutSubmodule($module);
    }
}



