<?php

class PoP_Module_Processor_PostCardLayouts extends PoP_Module_Processor_PostCardLayoutsBase
{
    public final const MODULE_LAYOUTPOST_CARD = 'layoutpost-card';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTPOST_CARD],
        );
    }
}



