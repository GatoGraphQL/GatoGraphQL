<?php

class UserStance_Module_Processor_Layouts extends UserStance_Module_Processor_StanceLayoutsBase
{
    public const MODULE_LAYOUTSTANCE = 'layout-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTSTANCE],
        );
    }
}



