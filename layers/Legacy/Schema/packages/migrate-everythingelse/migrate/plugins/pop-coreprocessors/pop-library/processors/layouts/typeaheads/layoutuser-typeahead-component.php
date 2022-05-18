<?php

class PoP_Module_Processor_UserTypeaheadComponentLayouts extends PoP_Module_Processor_UserTypeaheadComponentLayoutsBase
{
    public final const MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT = 'layoutuser-typeahead-component';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT],
        );
    }
}



