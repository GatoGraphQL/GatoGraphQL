<?php

class PoP_Module_Processor_EventDateAndTimeLayouts extends PoP_Module_Processor_EventDateAndTimeLayoutsBase
{
    public const MODULE_EM_LAYOUTEVENT_TABLECOL = 'em-layoutevent-tablecol';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUTEVENT_TABLECOL],
        );
    }
}



