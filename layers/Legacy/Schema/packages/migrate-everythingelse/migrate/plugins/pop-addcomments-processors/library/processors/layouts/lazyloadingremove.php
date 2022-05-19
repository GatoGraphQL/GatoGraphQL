<?php

class PoP_Module_Processor_LazyLoadingRemoveLayouts extends PoP_Module_Processor_LazyLoadingRemoveLayoutsBase
{
    public final const COMPONENT_SCRIPT_LAZYLOADINGREMOVE = 'script-lazyloading-remove';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCRIPT_LAZYLOADINGREMOVE],
        );
    }
}



