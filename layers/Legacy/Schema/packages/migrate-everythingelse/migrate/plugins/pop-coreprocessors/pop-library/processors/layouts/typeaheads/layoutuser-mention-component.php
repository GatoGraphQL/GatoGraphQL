<?php

class PoP_Module_Processor_UserMentionComponentLayouts extends PoP_Module_Processor_UserMentionComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTUSER_MENTION_COMPONENT = 'layoutuser-mention-component';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTUSER_MENTION_COMPONENT,
        );
    }
}



