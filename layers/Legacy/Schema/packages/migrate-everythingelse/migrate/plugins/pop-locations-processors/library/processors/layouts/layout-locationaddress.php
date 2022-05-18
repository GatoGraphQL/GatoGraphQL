<?php

class PoP_Module_Processor_LocationAddressLayouts extends PoP_Module_Processor_LocationAddressLayoutsBase
{
    public final const MODULE_EM_LAYOUT_LOCATIONADDRESS = 'em-layout-address';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUT_LOCATIONADDRESS],
        );
    }
}



