<?php

class PoP_Module_Processor_LocationAddressLayouts extends PoP_Module_Processor_LocationAddressLayoutsBase
{
    public final const COMPONENT_EM_LAYOUT_LOCATIONADDRESS = 'em-layout-address';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_LAYOUT_LOCATIONADDRESS],
        );
    }
}



