<?php

class PoP_Module_Processor_TagMentionComponentLayouts extends PoP_Module_Processor_TagMentionComponentLayoutsBase
{
    public final const MODULE_LAYOUTTAG_MENTION_COMPONENT = 'layouttag-mention-component';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTTAG_MENTION_COMPONENT],
        );
    }
}



