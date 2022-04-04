<?php

class PoP_Module_Processor_TagInfoLayouts extends PoP_Module_Processor_TagInfoLayoutsBase
{
    public final const MODULE_LAYOUT_TAGINFO = 'layout-taginfo';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_TAGINFO],
        );
    }
}



