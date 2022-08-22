<?php

class PoP_Module_Processor_Alerts extends PoP_Module_Processor_AlertsBase
{
    public final const COMPONENT_ALERT_STICKY = 'alert-sticky';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ALERT_STICKY,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_ALERT_STICKY:
                return [PoP_Module_Processor_AnnouncementSpeechBubbles::class, PoP_Module_Processor_AnnouncementSpeechBubbles::COMPONENT_ANNOUNCEMENTSPEECHBUBBLE_STICKY];
        }

        return parent::getLayoutSubcomponent($component);
    }
}



