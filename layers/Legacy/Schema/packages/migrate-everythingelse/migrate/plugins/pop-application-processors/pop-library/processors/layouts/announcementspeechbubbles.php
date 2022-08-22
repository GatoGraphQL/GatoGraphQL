<?php

class PoP_Module_Processor_AnnouncementSpeechBubbles extends PoP_Module_Processor_AnnouncementSpeechBubblesBase
{
    public final const COMPONENT_ANNOUNCEMENTSPEECHBUBBLE_STICKY = 'announcementspeechbubble-sticky';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ANNOUNCEMENTSPEECHBUBBLE_STICKY,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {

        // return self::COMPONENT_LAYOUT_PREVIEWPOST_NOTHUMB_STICKY;
        return [PoP_Module_Processor_StickyPostLayouts::class, PoP_Module_Processor_StickyPostLayouts::COMPONENT_LAYOUT_FULLVIEW_STICKY];
    }
}



