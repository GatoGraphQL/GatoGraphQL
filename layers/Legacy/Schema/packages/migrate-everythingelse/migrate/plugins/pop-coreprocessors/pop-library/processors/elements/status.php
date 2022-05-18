<?php

class PoP_Module_Processor_Status extends PoP_Module_Processor_StatusBase
{
    public final const COMPONENT_STATUS = 'status';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_STATUS],
        );
    }
}



