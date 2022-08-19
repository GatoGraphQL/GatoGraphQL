<?php

class PoP_Module_Processor_UserTypeaheadComponentLayouts extends PoP_Module_Processor_UserTypeaheadComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT = 'layoutuser-typeahead-component';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT,
        );
    }
}



