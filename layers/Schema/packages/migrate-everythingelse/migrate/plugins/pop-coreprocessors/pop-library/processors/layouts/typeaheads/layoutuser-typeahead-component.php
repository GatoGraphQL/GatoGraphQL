<?php

class PoP_Module_Processor_UserTypeaheadComponentLayouts extends PoP_Module_Processor_UserTypeaheadComponentLayoutsBase
{
    public const MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT = 'layoutuser-typeahead-component';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
        );
    }
}



