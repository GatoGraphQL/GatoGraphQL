<?php

class PoP_Module_Processor_Alerts extends PoP_Module_Processor_AlertsBase
{
    public final const COMPONENT_ALERT_STICKY = 'alert-sticky';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ALERT_STICKY],
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_ALERT_STICKY:
                return [PoP_Module_Processor_AnnouncementSpeechBubbles::class, PoP_Module_Processor_AnnouncementSpeechBubbles::COMPONENT_ANNOUNCEMENTSPEECHBUBBLE_STICKY];
        }

        return parent::getLayoutSubcomponent($component);
    }
}



