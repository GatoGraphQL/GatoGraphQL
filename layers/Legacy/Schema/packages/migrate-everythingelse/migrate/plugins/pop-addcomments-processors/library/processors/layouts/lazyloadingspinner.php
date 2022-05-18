<?php

class PoP_Module_Processor_LazyLoadingSpinnerLayouts extends PoP_Module_Processor_LazyLoadingSpinnerLayoutsBase
{
    public final const MODULE_LAYOUT_LAZYLOADINGSPINNER = 'layout-lazyloading-spinner';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_LAZYLOADINGSPINNER],
        );
    }
}



