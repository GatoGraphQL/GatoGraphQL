<?php

class PoP_Module_Processor_MapAddMarkers extends PoP_Module_Processor_MapAddMarkersBase
{
    public final const MODULE_MAP_ADDMARKER = 'em-map-addmarker';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_ADDMARKER],
        );
    }
}



