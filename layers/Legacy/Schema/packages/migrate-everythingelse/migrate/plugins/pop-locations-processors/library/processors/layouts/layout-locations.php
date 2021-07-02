<?php

class GD_EM_Module_Processor_LocationLayouts extends GD_EM_Module_Processor_LocationLayoutsBase
{
    public const MODULE_EM_LAYOUT_LOCATIONS = 'em-layout-locations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUT_LOCATIONS],
        );
    }
}



