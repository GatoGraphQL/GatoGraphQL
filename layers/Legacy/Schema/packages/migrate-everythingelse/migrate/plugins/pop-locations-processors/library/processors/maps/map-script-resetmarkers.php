<?php

class PoP_Module_Processor_MapResetMarkerScripts extends PoP_Module_Processor_MapResetMarkerScriptsBase
{
    public final const COMPONENT_MAP_SCRIPT_RESETMARKERS = 'em-map-script-resetmarkers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_SCRIPT_RESETMARKERS,
        );
    }
}



