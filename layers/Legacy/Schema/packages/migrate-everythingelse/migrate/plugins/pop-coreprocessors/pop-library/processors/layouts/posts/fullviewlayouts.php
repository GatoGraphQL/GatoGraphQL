<?php

class PoP_Module_Processor_StickyPostLayouts extends PoP_Module_Processor_FullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEW_STICKY = 'layout-fullview-sticky';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FULLVIEW_STICKY,
        );
    }
}



