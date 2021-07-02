<?php

class GD_EM_Module_Processor_LocationTypeaheadsSelectedLayouts extends GD_EM_Module_Processor_LocationTypeaheadsSelectedLayoutsBase
{
    public const MODULE_LAYOUTLOCATION_CARD = 'em-layoutlocation-card';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTLOCATION_CARD],
        );
    }
}



