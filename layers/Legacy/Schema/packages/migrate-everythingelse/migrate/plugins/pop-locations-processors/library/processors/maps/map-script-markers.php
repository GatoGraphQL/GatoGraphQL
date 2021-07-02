<?php

class PoP_Module_Processor_MapMarkerScripts extends PoP_Module_Processor_MapMarkerScriptsBase
{
    public const MODULE_MAP_SCRIPT_MARKERS = 'em-map-script-markers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_SCRIPT_MARKERS],
        );
    }
}



