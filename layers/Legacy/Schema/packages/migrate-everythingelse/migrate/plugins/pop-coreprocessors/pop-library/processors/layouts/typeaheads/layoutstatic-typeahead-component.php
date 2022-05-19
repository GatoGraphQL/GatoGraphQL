<?php

class PoP_Module_Processor_StaticTypeaheadComponentLayouts extends PoP_Module_Processor_StaticTypeaheadComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTSTATIC_TYPEAHEAD_COMPONENT = 'layoutstatic-typeahead-component';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTSTATIC_TYPEAHEAD_COMPONENT],
        );
    }
}



