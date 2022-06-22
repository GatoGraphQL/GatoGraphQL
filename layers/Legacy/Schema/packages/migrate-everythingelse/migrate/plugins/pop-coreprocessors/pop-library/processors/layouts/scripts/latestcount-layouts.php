<?php

class PoP_Module_Processor_LatestCountScriptsLayouts extends PoP_Module_Processor_LatestCountScriptsLayoutsBase
{
    public final const COMPONENT_LAYOUT_LATESTCOUNTSCRIPT = 'layout-latestcount-script';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_LATESTCOUNTSCRIPT,
        );
    }
}



