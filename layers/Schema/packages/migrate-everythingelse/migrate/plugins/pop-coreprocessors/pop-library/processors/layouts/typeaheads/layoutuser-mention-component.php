<?php

class PoP_Module_Processor_UserMentionComponentLayouts extends PoP_Module_Processor_UserMentionComponentLayoutsBase
{
    public const MODULE_LAYOUTUSER_MENTION_COMPONENT = 'layoutuser-mention-component';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTUSER_MENTION_COMPONENT],
        );
    }
}



