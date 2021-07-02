<?php

class PoP_Module_Processor_MapStaticImageLocations extends PoP_Module_Processor_MapStaticImageLocationsBase
{
    public const MODULE_MAP_STATICIMAGE_LOCATIONS = 'em-map-staticimage-locations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_STATICIMAGE_LOCATIONS],
        );
    }
}



