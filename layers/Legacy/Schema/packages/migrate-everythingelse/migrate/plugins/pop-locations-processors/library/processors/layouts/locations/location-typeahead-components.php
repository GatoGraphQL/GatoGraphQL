<?php

class GD_EM_Module_Processor_LocationTypeaheadsComponentLayouts extends GD_EM_Module_Processor_LocationTypeaheadsComponentLayoutsBase
{
    public final const MODULE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT = 'em-layoutlocation-typeahead-component';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT],
        );
    }
}



