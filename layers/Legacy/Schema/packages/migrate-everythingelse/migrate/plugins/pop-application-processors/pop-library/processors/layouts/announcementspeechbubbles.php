<?php

class PoP_Module_Processor_AnnouncementSpeechBubbles extends PoP_Module_Processor_AnnouncementSpeechBubblesBase
{
    public final const MODULE_ANNOUNCEMENTSPEECHBUBBLE_STICKY = 'announcementspeechbubble-sticky';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ANNOUNCEMENTSPEECHBUBBLE_STICKY],
        );
    }

    public function getLayoutSubmodule(array $component)
    {

        // return [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_NOTHUMB_STICKY];
        return [PoP_Module_Processor_StickyPostLayouts::class, PoP_Module_Processor_StickyPostLayouts::COMPONENT_LAYOUT_FULLVIEW_STICKY];
    }
}



