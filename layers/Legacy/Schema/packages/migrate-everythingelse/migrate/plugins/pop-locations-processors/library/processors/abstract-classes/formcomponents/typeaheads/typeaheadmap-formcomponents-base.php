<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_TypeaheadMapFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $ret[] = $this->getLocationsTypeaheadSubmodule($component);
        $ret[] = $this->getMapSubmodule($component);
        $ret[] = [PoP_Module_Processor_MapAddMarkers::class, PoP_Module_Processor_MapAddMarkers::COMPONENT_MAP_ADDMARKER];

        return $ret;
    }

    public function getFormcomponentModule(array $component)
    {
        return $this->getLocationsTypeaheadSubmodule($component);
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_TYPEAHEADMAP];
    }

    public function getMapSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::COMPONENT_MAP_INDIVIDUAL];
    }

    public function getLocationsTypeaheadSubmodule(array $component)
    {
        return null;
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $this->appendProp($component, $props, 'class', 'pop-typeaheadmap');

        $locations_typeahead = $this->getLocationsTypeaheadSubmodule($component);

        // Classes to define its frame
        $this->setProp($component, $props, 'wrapper-class', 'row');
        $this->setProp($component, $props, 'map-class', 'col-sm-9 col-sm-push-3');
        $this->setProp($component, $props, 'typeahead-class', 'col-sm-3 col-sm-pull-9');

        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['addmarker-module'] = [PoP_Module_Processor_MapAddMarkers::class, PoP_Module_Processor_MapAddMarkers::COMPONENT_MAP_ADDMARKER];

        $locations_typeahead = $this->getLocationsTypeaheadSubmodule($component);
        $map_component = $this->getMapSubmodule($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-individual'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['locations'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($locations_typeahead);

        $ret[GD_JS_CLASSES]['wrapper'] = $this->getProp($component, $props, 'wrapper-class');
        $ret[GD_JS_CLASSES]['map'] = $this->getProp($component, $props, 'map-class');
        $ret[GD_JS_CLASSES]['typeahead'] = $this->getProp($component, $props, 'typeahead-class');

        return $ret;
    }
}
