<?php

class PoP_Module_Processor_StickyPostLayouts extends PoP_Module_Processor_FullViewLayoutsBase
{
    public final const MODULE_LAYOUT_FULLVIEW_STICKY = 'layout-fullview-sticky';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW_STICKY],
        );
    }
}



