<?php

class PoP_Module_Processor_TagMentionComponentLayouts extends PoP_Module_Processor_TagMentionComponentLayoutsBase
{
    public const MODULE_LAYOUTTAG_MENTION_COMPONENT = 'layouttag-mention-component';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTTAG_MENTION_COMPONENT],
        );
    }
}



