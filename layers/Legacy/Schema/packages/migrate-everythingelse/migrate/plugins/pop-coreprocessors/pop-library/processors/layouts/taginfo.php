<?php

class PoP_Module_Processor_TagInfoLayouts extends PoP_Module_Processor_TagInfoLayoutsBase
{
    public final const COMPONENT_LAYOUT_TAGINFO = 'layout-taginfo';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_TAGINFO,
        );
    }
}



