<?php

class PoP_Module_Processor_PostTypeaheadComponentLayouts extends PoP_Module_Processor_PostTypeaheadComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTPOST_TYPEAHEAD_COMPONENT = 'layoutpost-typeahead-component';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTPOST_TYPEAHEAD_COMPONENT],
        );
    }
}



