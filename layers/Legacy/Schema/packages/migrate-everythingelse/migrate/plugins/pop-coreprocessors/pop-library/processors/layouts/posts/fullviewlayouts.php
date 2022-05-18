<?php

class PoP_Module_Processor_StickyPostLayouts extends PoP_Module_Processor_FullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEW_STICKY = 'layout-fullview-sticky';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW_STICKY],
        );
    }
}



