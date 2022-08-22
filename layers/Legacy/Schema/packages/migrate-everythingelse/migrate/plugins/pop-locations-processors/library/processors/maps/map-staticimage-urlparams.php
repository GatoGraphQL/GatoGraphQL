<?php

class PoP_Module_Processor_MapStaticImageURLParams extends PoP_Module_Processor_MapStaticImageURLParamsBase
{
    public final const COMPONENT_MAP_STATICIMAGE_URLPARAM = 'em-map-staticimage-urlparam';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_STATICIMAGE_URLPARAM,
        );
    }
}



