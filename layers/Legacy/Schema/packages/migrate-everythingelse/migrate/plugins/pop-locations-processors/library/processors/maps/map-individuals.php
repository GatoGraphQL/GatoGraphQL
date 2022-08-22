<?php

class PoP_Module_Processor_MapIndividuals extends PoP_Module_Processor_MapIndividualsBase
{
    public final const COMPONENT_MAP_INDIVIDUAL = 'em-map-individual';
    public final const COMPONENT_MAP_SIDEBARINDIVIDUAL = 'em-map-sidebarindividual';
    public final const COMPONENT_MAP_INDIVIDUAL_POST = 'em-map-individual-post';
    public final const COMPONENT_MAP_INDIVIDUAL_USER = 'em-map-individual-user';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MAP_INDIVIDUAL,
            self::COMPONENT_MAP_SIDEBARINDIVIDUAL,
            self::COMPONENT_MAP_INDIVIDUAL_POST,
            self::COMPONENT_MAP_INDIVIDUAL_USER,
        );
    }

    public function getMapdivSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MAP_INDIVIDUAL_POST:
            case self::COMPONENT_MAP_INDIVIDUAL_USER:
            case self::COMPONENT_MAP_SIDEBARINDIVIDUAL:
                return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAPSTATICIMAGE_USERORPOST_DIV];
        }

        return parent::getMapdivSubcomponent($component);
    }

    public function getDrawmarkersSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MAP_INDIVIDUAL_POST:
            case self::COMPONENT_MAP_INDIVIDUAL_USER:
            case self::COMPONENT_MAP_SIDEBARINDIVIDUAL:
                return [PoP_Module_Processor_MapDrawMarkerScripts::class, PoP_Module_Processor_MapDrawMarkerScripts::COMPONENT_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS];
        }

        return parent::getDrawmarkersSubcomponent($component);
    }

    public function openOnemarkerInfowindow(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MAP_SIDEBARINDIVIDUAL:
                return false;
        }

        return parent::openOnemarkerInfowindow($component);
    }
}


