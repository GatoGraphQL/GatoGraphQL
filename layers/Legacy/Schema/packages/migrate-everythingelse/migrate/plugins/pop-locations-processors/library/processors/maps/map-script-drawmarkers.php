<?php

class PoP_Module_Processor_MapDrawMarkerScripts extends PoP_Module_Processor_MapDrawMarkerScriptsBase
{
    public final const COMPONENT_MAP_SCRIPT_DRAWMARKERS = 'em-map-script-drawmarkers';
    public final const COMPONENT_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS = 'em-map-staticimage-script-drawmarkers';
    public final const COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS = 'em-map-staticimage-userorpost-script-drawmarkers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_SCRIPT_DRAWMARKERS,
            self::COMPONENT_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS,
            self::COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS,
        );
    }

    public function getMapdivSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS:
                return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAPSTATICIMAGE_DIV];

            case self::COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS:
                return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV];
        }
    
        return parent::getMapdivSubcomponent($component);
    }
}



