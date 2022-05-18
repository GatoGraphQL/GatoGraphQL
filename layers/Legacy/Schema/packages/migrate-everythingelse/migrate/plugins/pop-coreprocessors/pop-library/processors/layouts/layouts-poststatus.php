<?php

class PoP_Module_Processor_PostStatusLayouts extends PoP_Module_Processor_PostStatusLayoutsBase
{
    public final const MODULE_LAYOUTPOST_STATUS = 'layoutpost-status';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTPOST_STATUS],
        );
    }
}



