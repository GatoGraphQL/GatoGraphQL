<?php

class PoP_Module_Processor_LocationAddressLayouts extends PoP_Module_Processor_LocationAddressLayoutsBase
{
    public const MODULE_EM_LAYOUT_LOCATIONADDRESS = 'em-layout-address';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUT_LOCATIONADDRESS],
        );
    }
}



