<?php

class PoP_Module_Processor_LazyLoadingSpinnerLayouts extends PoP_Module_Processor_LazyLoadingSpinnerLayoutsBase
{
    public final const COMPONENT_LAYOUT_LAZYLOADINGSPINNER = 'layout-lazyloading-spinner';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_LAZYLOADINGSPINNER,
        );
    }
}



