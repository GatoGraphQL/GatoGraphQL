<?php

class PoP_Module_Processor_LatestCountScriptsLayouts extends PoP_Module_Processor_LatestCountScriptsLayoutsBase
{
    public final const MODULE_LAYOUT_LATESTCOUNTSCRIPT = 'layout-latestcount-script';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_LATESTCOUNTSCRIPT],
        );
    }
}



