<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_ScrollMapsBase extends PoP_Module_Processor_MultiplesBase
{
    public function getInnerSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        // if it's a map, add the Map block. Do it before adding the Scroll, because otherwise there's an error:
        // The map is not created yet, however the links in the elements are already trying to add the markers
        $ret[] = $this->getMapSubmodule($module);
        $ret[] = $this->getInnerSubmodule($module);

        return $ret;
    }

    public function getMapSubmodule(array $module)
    {
        if ($this->isPostMap($module)) {
            return [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_POST];
        } elseif ($this->isUserMap($module)) {
            return [GD_EM_Module_Processor_Maps::class, GD_EM_Module_Processor_Maps::MODULE_EM_MAP_USER];
        }

        return null;
    }

    protected function isPostMap(array $module)
    {
        return false;
    }

    protected function isUserMap(array $module)
    {
        return false;
    }

    public function getMapDirection(array $module, array &$props)
    {
        return 'vertical';
    }

    public function initModelProps(array $module, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Artificial property added to identify the template when adding module-resources
        $this->setProp($module, $props, 'resourceloader', 'map');
        $this->appendProp($module, $props, 'class', 'map');

        if ($this->isUserMap($module)) {
            $this->appendProp($module, $props, 'class', 'userscrollmap');
        } elseif ($this->isPostMap($module)) {
            $this->appendProp($module, $props, 'class', 'postscrollmap');
        }

        // By default the scrollmap is vertical
        $this->setProp($module, $props, 'direction', $this->getMapDirection($module, $props));
        $direction = $this->getProp($module, $props, 'direction');

        // Set the class on the block, so the vertical scrollMap will appear to the left of the map
        $this->appendProp($module, $props, 'class', $direction);

        // Set the direction on the ScrollMap
        $inner_module = $this->getInnerSubmodule($module);
        $this->setProp($inner_module, $props, 'direction', $direction);

        parent::initModelProps($module, $props);
    }
}
