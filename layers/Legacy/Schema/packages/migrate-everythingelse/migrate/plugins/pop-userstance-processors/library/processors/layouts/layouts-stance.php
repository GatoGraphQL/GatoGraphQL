<?php

class UserStance_Module_Processor_Layouts extends UserStance_Module_Processor_StanceLayoutsBase
{
    public final const COMPONENT_LAYOUTSTANCE = 'layout-stance';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTSTANCE,
        );
    }
}



