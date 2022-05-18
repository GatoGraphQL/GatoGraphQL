<?php

class PoP_Module_Processor_MapIndividuals extends PoP_Module_Processor_MapIndividualsBase
{
    public final const COMPONENT_MAP_INDIVIDUAL = 'em-map-individual';
    public final const COMPONENT_MAP_SIDEBARINDIVIDUAL = 'em-map-sidebarindividual';
    public final const COMPONENT_MAP_INDIVIDUAL_POST = 'em-map-individual-post';
    public final const COMPONENT_MAP_INDIVIDUAL_USER = 'em-map-individual-user';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MAP_INDIVIDUAL],
            [self::class, self::COMPONENT_MAP_SIDEBARINDIVIDUAL],
            [self::class, self::COMPONENT_MAP_INDIVIDUAL_POST],
            [self::class, self::COMPONENT_MAP_INDIVIDUAL_USER],
        );
    }

    public function getMapdivSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MAP_INDIVIDUAL_POST:
            case self::COMPONENT_MAP_INDIVIDUAL_USER:
            case self::COMPONENT_MAP_SIDEBARINDIVIDUAL:
                return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV];
        }

        return parent::getMapdivSubmodule($component);
    }

    public function getDrawmarkersSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MAP_INDIVIDUAL_POST:
            case self::COMPONENT_MAP_INDIVIDUAL_USER:
            case self::COMPONENT_MAP_SIDEBARINDIVIDUAL:
                return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
        }

        return parent::getDrawmarkersSubmodule($component);
    }

    public function openOnemarkerInfowindow(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MAP_SIDEBARINDIVIDUAL:
                return false;
        }

        return parent::openOnemarkerInfowindow($component);
    }
}


