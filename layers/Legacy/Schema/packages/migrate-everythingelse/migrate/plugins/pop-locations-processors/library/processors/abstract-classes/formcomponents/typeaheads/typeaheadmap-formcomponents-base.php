<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_TypeaheadMapFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $ret[] = $this->getLocationsTypeaheadSubmodule($componentVariation);
        $ret[] = $this->getMapSubmodule($componentVariation);
        $ret[] = [PoP_Module_Processor_MapAddMarkers::class, PoP_Module_Processor_MapAddMarkers::MODULE_MAP_ADDMARKER];

        return $ret;
    }

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getLocationsTypeaheadSubmodule($componentVariation);
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_TYPEAHEADMAP];
    }

    public function getMapSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::MODULE_MAP_INDIVIDUAL];
    }

    public function getLocationsTypeaheadSubmodule(array $componentVariation)
    {
        return null;
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);
        parent::initRequestProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $this->appendProp($componentVariation, $props, 'class', 'pop-typeaheadmap');

        $locations_typeahead = $this->getLocationsTypeaheadSubmodule($componentVariation);

        // Classes to define its frame
        $this->setProp($componentVariation, $props, 'wrapper-class', 'row');
        $this->setProp($componentVariation, $props, 'map-class', 'col-sm-9 col-sm-push-3');
        $this->setProp($componentVariation, $props, 'typeahead-class', 'col-sm-3 col-sm-pull-9');

        parent::initModelProps($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['addmarker-module'] = [PoP_Module_Processor_MapAddMarkers::class, PoP_Module_Processor_MapAddMarkers::MODULE_MAP_ADDMARKER];

        $locations_typeahead = $this->getLocationsTypeaheadSubmodule($componentVariation);
        $map_componentVariation = $this->getMapSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-individual'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['locations'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($locations_typeahead);

        $ret[GD_JS_CLASSES]['wrapper'] = $this->getProp($componentVariation, $props, 'wrapper-class');
        $ret[GD_JS_CLASSES]['map'] = $this->getProp($componentVariation, $props, 'map-class');
        $ret[GD_JS_CLASSES]['typeahead'] = $this->getProp($componentVariation, $props, 'typeahead-class');

        return $ret;
    }
}
