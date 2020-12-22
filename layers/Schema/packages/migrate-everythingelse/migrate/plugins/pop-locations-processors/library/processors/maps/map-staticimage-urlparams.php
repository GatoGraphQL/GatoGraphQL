<?php

class PoP_Module_Processor_MapStaticImageURLParams extends PoP_Module_Processor_MapStaticImageURLParamsBase
{
    public const MODULE_MAP_STATICIMAGE_URLPARAM = 'em-map-staticimage-urlparam';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_STATICIMAGE_URLPARAM],
        );
    }
}



