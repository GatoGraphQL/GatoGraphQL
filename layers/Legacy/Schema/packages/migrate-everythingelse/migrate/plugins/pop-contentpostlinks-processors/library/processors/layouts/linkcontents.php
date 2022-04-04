<?php

class PoP_ContentPostLinks_Module_Processor_LinkContentLayouts extends PoP_Module_Processor_LinkContentLayoutsBase
{
    public final const MODULE_LAYOUT_CONTENT_LINK = 'layout-content-link';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CONTENT_LINK],
        );
    }
}



