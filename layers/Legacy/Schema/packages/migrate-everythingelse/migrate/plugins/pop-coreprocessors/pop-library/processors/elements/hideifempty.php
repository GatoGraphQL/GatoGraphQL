<?php

class PoP_Module_Processor_HideIfEmpties extends PoP_Module_Processor_HideIfEmptyBase
{
    public final const MODULE_HIDEIFEMPTY = 'hideifempty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_HIDEIFEMPTY],
        );
    }
}



