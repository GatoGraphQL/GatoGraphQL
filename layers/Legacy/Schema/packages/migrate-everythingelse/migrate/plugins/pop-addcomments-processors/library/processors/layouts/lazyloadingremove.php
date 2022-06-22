<?php

class PoP_Module_Processor_LazyLoadingRemoveLayouts extends PoP_Module_Processor_LazyLoadingRemoveLayoutsBase
{
    public final const COMPONENT_SCRIPT_LAZYLOADINGREMOVE = 'script-lazyloading-remove';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCRIPT_LAZYLOADINGREMOVE,
        );
    }
}



