<?php

class PoP_Module_Processor_HideIfEmpties extends PoP_Module_Processor_HideIfEmptyBase
{
    public final const COMPONENT_HIDEIFEMPTY = 'hideifempty';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_HIDEIFEMPTY,
        );
    }
}



