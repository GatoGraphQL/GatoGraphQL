<?php

class PoP_Module_Processor_CustomFullUserTitleLayouts extends PoP_Module_Processor_FullUserTitleLayoutsBase
{
    public final const MODULE_LAYOUT_FULLUSERTITLE = 'singlelayout-fullusertitle';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLUSERTITLE],
        );
    }
}



