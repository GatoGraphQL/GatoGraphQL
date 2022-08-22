<?php

class PoP_Module_Processor_MapMarkerScripts extends PoP_Module_Processor_MapMarkerScriptsBase
{
    public final const COMPONENT_MAP_SCRIPT_MARKERS = 'em-map-script-markers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_SCRIPT_MARKERS,
        );
    }
}



