<?php

class PoP_Module_Processor_CustomFullUserTitleLayouts extends PoP_Module_Processor_FullUserTitleLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLUSERTITLE = 'singlelayout-fullusertitle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLUSERTITLE],
        );
    }
}



