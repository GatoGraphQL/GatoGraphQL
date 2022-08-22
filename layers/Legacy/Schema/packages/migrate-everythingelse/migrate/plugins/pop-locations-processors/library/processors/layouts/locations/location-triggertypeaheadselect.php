<?php

class PoP_Module_Processor_TriggerLocationTypeaheadScriptLayouts extends PoP_Module_Processor_TriggerLocationTypeaheadScriptLayoutsBase
{
    public final const COMPONENT_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION = 'em-script-triggertypeaheadselect-location';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION,
        );
    }
}



