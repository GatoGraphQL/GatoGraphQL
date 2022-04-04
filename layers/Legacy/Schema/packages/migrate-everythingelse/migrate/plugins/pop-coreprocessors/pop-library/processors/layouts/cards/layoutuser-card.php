<?php

class PoP_Module_Processor_UserCardLayouts extends PoP_Module_Processor_UserCardLayoutsBase
{
    public final const MODULE_LAYOUTUSER_CARD = 'layoutuser-card';
    public final const MODULE_LAYOUTUSER_FILTERCARD = 'layoutuser-filtercard';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTUSER_CARD],
            [self::class, self::MODULE_LAYOUTUSER_FILTERCARD],
        );
    }
}



