<?php

class PoP_Module_Processor_Status extends PoP_Module_Processor_StatusBase
{
    public final const MODULE_STATUS = 'status';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_STATUS],
        );
    }
}



