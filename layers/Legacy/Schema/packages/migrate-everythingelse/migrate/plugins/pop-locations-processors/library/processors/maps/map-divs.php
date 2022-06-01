<?php

class PoP_Module_Processor_MapDivs extends PoP_Module_Processor_MapDivsBase
{
    public final const COMPONENT_MAP_DIV = 'em-map-div';
    public final const COMPONENT_MAPSTATICIMAGE_DIV = 'em-mapstaticimage-div';
    public final const COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV = 'em-mapstaticimage-userorpost-div';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MAP_DIV],
            [self::class, self::COMPONENT_MAPSTATICIMAGE_DIV],
            [self::class, self::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV],
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MAPSTATICIMAGE_DIV:
                $ret[] = [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STATICIMAGE];
                break;

            case self::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV:
                $ret[] = [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::COMPONENT_SCROLL_STATICIMAGE_USERORPOST];
                break;
        }

        return $ret;
    }
}



