<?php

class GD_EM_Module_Processor_Maps extends GD_EM_Module_Processor_MapsBase
{
    public final const COMPONENT_EM_MAP_POST = 'em-map-post';
    public final const COMPONENT_EM_MAP_USER = 'em-map-user';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_MAP_POST],
            [self::class, self::COMPONENT_EM_MAP_USER],
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_EM_MAP_POST => [GD_EM_Module_Processor_MapInners::class, GD_EM_Module_Processor_MapInners::COMPONENT_EM_MAPINNER_POST],
            self::COMPONENT_EM_MAP_USER => [GD_EM_Module_Processor_MapInners::class, GD_EM_Module_Processor_MapInners::COMPONENT_EM_MAPINNER_USER],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



