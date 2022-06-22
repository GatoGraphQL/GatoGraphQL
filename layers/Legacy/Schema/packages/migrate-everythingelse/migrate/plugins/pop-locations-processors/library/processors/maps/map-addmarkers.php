<?php

class PoP_Module_Processor_MapAddMarkers extends PoP_Module_Processor_MapAddMarkersBase
{
    public final const COMPONENT_MAP_ADDMARKER = 'em-map-addmarker';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_ADDMARKER,
        );
    }
}



