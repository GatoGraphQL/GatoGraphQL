<?php

class PoP_ContentPostLinks_Module_Processor_LinkContentLayouts extends PoP_Module_Processor_LinkContentLayoutsBase
{
    public final const COMPONENT_LAYOUT_CONTENT_LINK = 'layout-content-link';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_CONTENT_LINK,
        );
    }
}



