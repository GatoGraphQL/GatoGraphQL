<?php

class PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts extends PoP_Module_Processor_TriggerLocationTypeaheadScriptLayoutsBase
{
    public final const MODULE_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION = 'em-script-triggertypeaheadselect-location';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }
}



