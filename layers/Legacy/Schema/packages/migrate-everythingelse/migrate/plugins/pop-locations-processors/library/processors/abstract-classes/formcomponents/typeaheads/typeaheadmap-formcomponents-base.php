<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_TypeaheadMapFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        $ret[] = $this->getLocationsTypeaheadSubmodule($module);
        $ret[] = $this->getMapSubmodule($module);
        $ret[] = [PoP_Module_Processor_MapAddMarkers::class, PoP_Module_Processor_MapAddMarkers::MODULE_MAP_ADDMARKER];

        return $ret;
    }

    public function getFormcomponentModule(array $module)
    {
        return $this->getLocationsTypeaheadSubmodule($module);
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_TYPEAHEADMAP];
    }

    public function getMapSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::MODULE_MAP_INDIVIDUAL];
    }

    public function getLocationsTypeaheadSubmodule(array $module)
    {
        return null;
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($module, $props);
        parent::initRequestProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $this->appendProp($module, $props, 'class', 'pop-typeaheadmap');

        $locations_typeahead = $this->getLocationsTypeaheadSubmodule($module);

        // Classes to define its frame
        $this->setProp($module, $props, 'wrapper-class', 'row');
        $this->setProp($module, $props, 'map-class', 'col-sm-9 col-sm-push-3');
        $this->setProp($module, $props, 'typeahead-class', 'col-sm-3 col-sm-pull-9');

        parent::initModelProps($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['addmarker-module'] = [PoP_Module_Processor_MapAddMarkers::class, PoP_Module_Processor_MapAddMarkers::MODULE_MAP_ADDMARKER];

        $locations_typeahead = $this->getLocationsTypeaheadSubmodule($module);
        $map_module = $this->getMapSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-individual'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['locations'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($locations_typeahead);

        $ret[GD_JS_CLASSES]['wrapper'] = $this->getProp($module, $props, 'wrapper-class');
        $ret[GD_JS_CLASSES]['map'] = $this->getProp($module, $props, 'map-class');
        $ret[GD_JS_CLASSES]['typeahead'] = $this->getProp($module, $props, 'typeahead-class');

        return $ret;
    }
}
