<?php

class PoP_Module_Processor_AnnouncementSpeechBubbles extends PoP_Module_Processor_AnnouncementSpeechBubblesBase
{
    public final const MODULE_ANNOUNCEMENTSPEECHBUBBLE_STICKY = 'announcementspeechbubble-sticky';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANNOUNCEMENTSPEECHBUBBLE_STICKY],
        );
    }

    public function getLayoutSubmodule(array $module)
    {

        // return [self::class, self::MODULE_LAYOUT_PREVIEWPOST_NOTHUMB_STICKY];
        return [PoP_Module_Processor_StickyPostLayouts::class, PoP_Module_Processor_StickyPostLayouts::MODULE_LAYOUT_FULLVIEW_STICKY];
    }
}



