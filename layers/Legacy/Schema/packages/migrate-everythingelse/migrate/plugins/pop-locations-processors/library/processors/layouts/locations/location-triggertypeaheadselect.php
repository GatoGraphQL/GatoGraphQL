<?php

class PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts extends PoP_Module_Processor_TriggerLocationTypeaheadScriptLayoutsBase
{
    public final const COMPONENT_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION = 'em-script-triggertypeaheadselect-location';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }
}



