<?php

class PoP_ContentPostLinks_Module_Processor_Layouts extends Wassup_Module_Processor_LinkAccessLayoutsBase
{
    public final const COMPONENT_LAYOUT_LINK_ACCESS = 'layout-link-access';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_LINK_ACCESS],
        );
    }
}



