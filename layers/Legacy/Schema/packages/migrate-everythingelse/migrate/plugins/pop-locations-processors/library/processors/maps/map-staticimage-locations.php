<?php

class PoP_Module_Processor_MapStaticImageLocations extends PoP_Module_Processor_MapStaticImageLocationsBase
{
    public final const COMPONENT_MAP_STATICIMAGE_LOCATIONS = 'em-map-staticimage-locations';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_STATICIMAGE_LOCATIONS,
        );
    }
}



