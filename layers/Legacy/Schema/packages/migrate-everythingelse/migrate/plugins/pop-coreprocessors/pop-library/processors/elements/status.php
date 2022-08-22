<?php

class PoP_Module_Processor_Status extends PoP_Module_Processor_StatusBase
{
    public final const COMPONENT_STATUS = 'status';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_STATUS,
        );
    }
}



