<?php

class PoP_Module_Processor_TagMentionComponentLayouts extends PoP_Module_Processor_TagMentionComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTTAG_MENTION_COMPONENT = 'layouttag-mention-component';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTTAG_MENTION_COMPONENT,
        );
    }
}



