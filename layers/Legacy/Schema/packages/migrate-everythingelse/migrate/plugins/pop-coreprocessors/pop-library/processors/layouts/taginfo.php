<?php

class PoP_Module_Processor_TagInfoLayouts extends PoP_Module_Processor_TagInfoLayoutsBase
{
    public final const MODULE_LAYOUT_TAGINFO = 'layout-taginfo';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_TAGINFO],
        );
    }
}



