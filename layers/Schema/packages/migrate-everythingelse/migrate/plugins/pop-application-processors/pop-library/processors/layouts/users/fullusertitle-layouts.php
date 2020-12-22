<?php

class PoP_Module_Processor_CustomFullUserTitleLayouts extends PoP_Module_Processor_FullUserTitleLayoutsBase
{
    public const MODULE_LAYOUT_FULLUSERTITLE = 'singlelayout-fullusertitle';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLUSERTITLE],
        );
    }
}



