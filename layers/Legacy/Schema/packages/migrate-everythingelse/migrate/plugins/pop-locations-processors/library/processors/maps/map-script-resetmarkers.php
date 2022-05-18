<?php

class PoP_Module_Processor_MapResetMarkerScripts extends PoP_Module_Processor_MapResetMarkerScriptsBase
{
    public final const MODULE_MAP_SCRIPT_RESETMARKERS = 'em-map-script-resetmarkers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_SCRIPT_RESETMARKERS],
        );
    }
}



