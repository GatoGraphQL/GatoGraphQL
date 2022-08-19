<?php

class PoP_Module_Processor_StaticTypeaheadComponentLayouts extends PoP_Module_Processor_StaticTypeaheadComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTSTATIC_TYPEAHEAD_COMPONENT = 'layoutstatic-typeahead-component';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTSTATIC_TYPEAHEAD_COMPONENT,
        );
    }
}



