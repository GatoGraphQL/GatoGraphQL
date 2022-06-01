<?php

class PoP_Module_Processor_TagTypeaheadComponentLayouts extends PoP_Module_Processor_TagTypeaheadComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTTAG_TYPEAHEAD_COMPONENT = 'layouttag-typeahead-component';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTTAG_TYPEAHEAD_COMPONENT,
        );
    }
}



