<?php

class PoP_Module_Processor_StickyPostLayouts extends PoP_Module_Processor_FullViewLayoutsBase
{
    public const MODULE_LAYOUT_FULLVIEW_STICKY = 'layout-fullview-sticky';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW_STICKY],
        );
    }
}



