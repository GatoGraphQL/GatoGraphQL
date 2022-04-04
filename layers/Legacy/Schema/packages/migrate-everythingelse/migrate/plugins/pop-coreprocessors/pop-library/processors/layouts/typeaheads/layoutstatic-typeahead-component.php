<?php

class PoP_Module_Processor_StaticTypeaheadComponentLayouts extends PoP_Module_Processor_StaticTypeaheadComponentLayoutsBase
{
    public final const MODULE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT = 'layoutstatic-typeahead-component';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT],
        );
    }
}



