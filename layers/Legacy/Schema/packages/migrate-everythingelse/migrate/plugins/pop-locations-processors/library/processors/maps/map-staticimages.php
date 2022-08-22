<?php

class PoP_Module_Processor_MapStaticImages extends PoP_Module_Processor_MapStaticImagesBase
{
    public final const COMPONENT_MAP_STATICIMAGE = 'em-map-staticimage';
    public final const COMPONENT_MAP_STATICIMAGE_USERORPOST = 'em-map-staticimage-userorpost';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_STATICIMAGE,
            self::COMPONENT_MAP_STATICIMAGE_USERORPOST,
        );
    }

    public function getUrlparamSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MAP_STATICIMAGE:
                return [PoP_Module_Processor_MapStaticImageURLParams::class, PoP_Module_Processor_MapStaticImageURLParams::COMPONENT_MAP_STATICIMAGE_URLPARAM];

            case self::COMPONENT_MAP_STATICIMAGE_USERORPOST:
                return [PoP_Module_Processor_MapStaticImageLocations::class, PoP_Module_Processor_MapStaticImageLocations::COMPONENT_MAP_STATICIMAGE_LOCATIONS];
        }

        return parent::getUrlparamSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MAP_STATICIMAGE:
            case self::COMPONENT_MAP_STATICIMAGE_USERORPOST:
                $this->appendProp($component, $props, 'class', 'img-responsive');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



