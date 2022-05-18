<?php

class GD_EM_Module_Processor_LocationTypeaheadsSelectedLayouts extends GD_EM_Module_Processor_LocationTypeaheadsSelectedLayoutsBase
{
    public final const MODULE_LAYOUTLOCATION_CARD = 'em-layoutlocation-card';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTLOCATION_CARD],
        );
    }
}



