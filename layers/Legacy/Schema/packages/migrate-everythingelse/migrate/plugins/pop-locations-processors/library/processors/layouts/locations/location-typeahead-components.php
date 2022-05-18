<?php

class GD_EM_Module_Processor_LocationTypeaheadsComponentLayouts extends GD_EM_Module_Processor_LocationTypeaheadsComponentLayoutsBase
{
    public final const COMPONENT_LAYOUTLOCATION_TYPEAHEAD_COMPONENT = 'em-layoutlocation-typeahead-component';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTLOCATION_TYPEAHEAD_COMPONENT],
        );
    }
}



