<?php

class PoP_Module_Processor_MapMarkerScripts extends PoP_Module_Processor_MapMarkerScriptsBase
{
    public final const COMPONENT_MAP_SCRIPT_MARKERS = 'em-map-script-markers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MAP_SCRIPT_MARKERS],
        );
    }
}



