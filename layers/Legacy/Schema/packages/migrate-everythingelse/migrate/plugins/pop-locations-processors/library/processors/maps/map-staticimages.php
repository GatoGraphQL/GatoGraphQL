<?php

class PoP_Module_Processor_MapStaticImages extends PoP_Module_Processor_MapStaticImagesBase
{
    public final const MODULE_MAP_STATICIMAGE = 'em-map-staticimage';
    public final const MODULE_MAP_STATICIMAGE_USERORPOST = 'em-map-staticimage-userorpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_STATICIMAGE],
            [self::class, self::MODULE_MAP_STATICIMAGE_USERORPOST],
        );
    }

    public function getUrlparamSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_MAP_STATICIMAGE:
                return [PoP_Module_Processor_MapStaticImageURLParams::class, PoP_Module_Processor_MapStaticImageURLParams::MODULE_MAP_STATICIMAGE_URLPARAM];

            case self::MODULE_MAP_STATICIMAGE_USERORPOST:
                return [PoP_Module_Processor_MapStaticImageLocations::class, PoP_Module_Processor_MapStaticImageLocations::MODULE_MAP_STATICIMAGE_LOCATIONS];
        }

        return parent::getUrlparamSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_MAP_STATICIMAGE:
            case self::MODULE_MAP_STATICIMAGE_USERORPOST:
                $this->appendProp($component, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



