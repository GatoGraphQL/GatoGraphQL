<?php

class PoP_Module_Processor_HideIfEmpties extends PoP_Module_Processor_HideIfEmptyBase
{
    public const MODULE_HIDEIFEMPTY = 'hideifempty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HIDEIFEMPTY],
        );
    }
}



