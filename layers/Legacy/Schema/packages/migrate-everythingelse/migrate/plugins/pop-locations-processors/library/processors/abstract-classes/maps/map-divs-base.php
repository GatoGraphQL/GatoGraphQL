<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_MapDivsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_DIV];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($inners = $this->getInnerSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    public function getInnerSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($inners = $this->getInnerSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $inners
            );
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'map');

        return $ret;
    }

    public function openOnemarkerInfowindow(array $componentVariation, array &$props)
    {
        return true;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Open the infoWindow automatically when the map has only 1 marker?
        $this->setProp($componentVariation, $props, 'open-onemarker-infowindow', $this->openOnemarkerInfowindow($componentVariation, $props));
        if ($this->getProp($componentVariation, $props, 'open-onemarker-infowindow')) {
            $this->mergeProp(
                $componentVariation,
                $props,
                'params',
                array(
                    'data-open-onemarker-infowindow' => true
                )
            );
        }

        parent::initModelProps($componentVariation, $props);
    }
}
