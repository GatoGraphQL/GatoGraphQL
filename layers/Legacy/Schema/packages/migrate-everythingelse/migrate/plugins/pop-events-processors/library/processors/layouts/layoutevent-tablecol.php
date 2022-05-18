<?php

class PoP_Module_Processor_EventDateAndTimeLayouts extends PoP_Module_Processor_EventDateAndTimeLayoutsBase
{
    public final const MODULE_EM_LAYOUTEVENT_TABLECOL = 'em-layoutevent-tablecol';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUTEVENT_TABLECOL],
        );
    }
}



