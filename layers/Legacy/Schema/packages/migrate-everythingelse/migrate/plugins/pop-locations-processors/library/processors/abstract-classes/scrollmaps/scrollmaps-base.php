<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_ScrollMapsBase extends PoP_Module_Processor_MultiplesBase
{
    public function getInnerSubmodule(array $component)
    {
        return null;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        // if it's a map, add the Map block. Do it before adding the Scroll, because otherwise there's an error:
        // The map is not created yet, however the links in the elements are already trying to add the markers
        $ret[] = $this->getMapSubmodule($component);
        $ret[] = $this->getInnerSubmodule($component);

        return $ret;
    }

    public function getMapSubmodule(array $component)
    {
        if ($this->isPostMap($component)) {
            return [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_POST];
        } elseif ($this->isUserMap($component)) {
            return [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_USER];
        }

        return null;
    }

    protected function isPostMap(array $component)
    {
        return false;
    }

    protected function isUserMap(array $component)
    {
        return false;
    }

    public function getMapDirection(array $component, array &$props)
    {
        return 'vertical';
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Artificial property added to identify the template when adding module-resources
        $this->setProp($component, $props, 'resourceloader', 'map');
        $this->appendProp($component, $props, 'class', 'map');

        if ($this->isUserMap($component)) {
            $this->appendProp($component, $props, 'class', 'userscrollmap');
        } elseif ($this->isPostMap($component)) {
            $this->appendProp($component, $props, 'class', 'postscrollmap');
        }

        // By default the scrollmap is vertical
        $this->setProp($component, $props, 'direction', $this->getMapDirection($component, $props));
        $direction = $this->getProp($component, $props, 'direction');

        // Set the class on the block, so the vertical scrollMap will appear to the left of the map
        $this->appendProp($component, $props, 'class', $direction);

        // Set the direction on the ScrollMap
        $inner_component = $this->getInnerSubmodule($component);
        $this->setProp($inner_component, $props, 'direction', $direction);

        parent::initModelProps($component, $props);
    }
}
