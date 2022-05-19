<?php

class PoP_Module_Processor_TagTypeaheadComponentLayouts extends PoP_Module_Processor_TagTypeaheadComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTTAG_TYPEAHEAD_COMPONENT = 'layouttag-typeahead-component';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTTAG_TYPEAHEAD_COMPONENT],
        );
    }
}



