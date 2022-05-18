<?php

class UserStance_Module_Processor_Layouts extends UserStance_Module_Processor_StanceLayoutsBase
{
    public final const MODULE_LAYOUTSTANCE = 'layout-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTSTANCE],
        );
    }
}



