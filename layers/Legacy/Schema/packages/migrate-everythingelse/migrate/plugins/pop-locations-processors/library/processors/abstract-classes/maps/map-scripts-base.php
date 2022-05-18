<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_MapScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT];
    }

    public function getCustomizationSubmodule(array $component)
    {
        return null;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($script_customize = $this->getCustomizationSubmodule($component)) {
            $ret[] = $script_customize;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        return [
            new RelationalModuleField(
                'locations',
                [
                    [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS],
                ]
            ),
        ];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($script_customize = $this->getCustomizationSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-customize'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($script_customize);
        }
        $markers = [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS];
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($markers);

        return $ret;
    }
}
