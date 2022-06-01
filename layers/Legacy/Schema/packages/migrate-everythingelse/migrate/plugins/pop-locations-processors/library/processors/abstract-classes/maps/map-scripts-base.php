<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;

abstract class PoP_Module_Processor_MapScriptsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT];
    }

    public function getCustomizationSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($script_customize = $this->getCustomizationSubcomponent($component)) {
            $ret[] = $script_customize;
        }

        return $ret;
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array
    {
        return [
            new RelationalComponentField(
                'locations',
                [
                    [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS],
                ]
            ),
        ];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($script_customize = $this->getCustomizationSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-customize'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($script_customize);
        }
        $markers = [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS];
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($markers);

        return $ret;
    }
}
