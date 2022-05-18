<?php

class PoP_Module_Processor_MapDrawMarkerScripts extends PoP_Module_Processor_MapDrawMarkerScriptsBase
{
    public final const MODULE_MAP_SCRIPT_DRAWMARKERS = 'em-map-script-drawmarkers';
    public final const MODULE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS = 'em-map-staticimage-script-drawmarkers';
    public final const MODULE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS = 'em-map-staticimage-userorpost-script-drawmarkers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_SCRIPT_DRAWMARKERS],
            [self::class, self::MODULE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS],
            [self::class, self::MODULE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS],
        );
    }

    public function getMapdivSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS:
                return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_DIV];

            case self::MODULE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS:
                return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAPSTATICIMAGE_USERORPOST_DIV];
        }
    
        return parent::getMapdivSubmodule($module);
    }
}



