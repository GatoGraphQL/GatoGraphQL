<?php

class PoP_Module_Processor_EventDateAndTimeLayouts extends PoP_Module_Processor_EventDateAndTimeLayoutsBase
{
    public final const COMPONENT_EM_LAYOUTEVENT_TABLECOL = 'em-layoutevent-tablecol';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_LAYOUTEVENT_TABLECOL,
        );
    }
}



