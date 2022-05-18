<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_ScrollMapsBase extends PoP_Module_Processor_MultiplesBase
{
    public function getInnerSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        // if it's a map, add the Map block. Do it before adding the Scroll, because otherwise there's an error:
        // The map is not created yet, however the links in the elements are already trying to add the markers
        $ret[] = $this->getMapSubmodule($componentVariation);
        $ret[] = $this->getInnerSubmodule($componentVariation);

        return $ret;
    }

    public function getMapSubmodule(array $componentVariation)
    {
        if ($this->isPostMap($componentVariation)) {
            return [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_POST];
        } elseif ($this->isUserMap($componentVariation)) {
            return [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_USER];
        }

        return null;
    }

    protected function isPostMap(array $componentVariation)
    {
        return false;
    }

    protected function isUserMap(array $componentVariation)
    {
        return false;
    }

    public function getMapDirection(array $componentVariation, array &$props)
    {
        return 'vertical';
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Artificial property added to identify the template when adding module-resources
        $this->setProp($componentVariation, $props, 'resourceloader', 'map');
        $this->appendProp($componentVariation, $props, 'class', 'map');

        if ($this->isUserMap($componentVariation)) {
            $this->appendProp($componentVariation, $props, 'class', 'userscrollmap');
        } elseif ($this->isPostMap($componentVariation)) {
            $this->appendProp($componentVariation, $props, 'class', 'postscrollmap');
        }

        // By default the scrollmap is vertical
        $this->setProp($componentVariation, $props, 'direction', $this->getMapDirection($componentVariation, $props));
        $direction = $this->getProp($componentVariation, $props, 'direction');

        // Set the class on the block, so the vertical scrollMap will appear to the left of the map
        $this->appendProp($componentVariation, $props, 'class', $direction);

        // Set the direction on the ScrollMap
        $inner_componentVariation = $this->getInnerSubmodule($componentVariation);
        $this->setProp($inner_componentVariation, $props, 'direction', $direction);

        parent::initModelProps($componentVariation, $props);
    }
}
