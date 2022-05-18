<?php

class PoP_ContentPostLinks_Module_Processor_LinkContentLayouts extends PoP_Module_Processor_LinkContentLayoutsBase
{
    public final const MODULE_LAYOUT_CONTENT_LINK = 'layout-content-link';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_CONTENT_LINK],
        );
    }
}



