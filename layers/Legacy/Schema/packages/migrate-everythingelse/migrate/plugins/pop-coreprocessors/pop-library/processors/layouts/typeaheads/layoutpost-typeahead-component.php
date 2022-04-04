<?php

class PoP_Module_Processor_PostTypeaheadComponentLayouts extends PoP_Module_Processor_PostTypeaheadComponentLayoutsBase
{
    public final const MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT = 'layoutpost-typeahead-component';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
        );
    }
}



