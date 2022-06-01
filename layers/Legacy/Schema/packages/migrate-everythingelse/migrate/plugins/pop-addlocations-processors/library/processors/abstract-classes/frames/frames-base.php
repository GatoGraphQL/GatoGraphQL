<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_CreateLocationFramesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_AddLocations_TemplateResourceLoaderProcessor::class, PoP_AddLocations_TemplateResourceLoaderProcessor::RESOURCE_FRAME_CREATELOCATIONMAP];
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'formMapLocationGeocode');
        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-map-locationgeocode');
        parent::initModelProps($component, $props);
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        switch ($component->name) {
            case self::COMPONENT_FRAME_CREATELOCATIONMAP:
                return array(
                    $this->getMapdivSubcomponent($component),
                    $this->getFormSubcomponent($component)
                );
        }

        return parent::getSubcomponents($component);
    }

    public function getFormSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [GD_EM_Module_Processor_CreateLocationForms::class, GD_EM_Module_Processor_CreateLocationForms::COMPONENT_FORM_CREATELOCATION];
    }

    public function getMapdivSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::COMPONENT_MAP_DIV];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $mapdiv = $this->getMapdivSubcomponent($component);
        $form = $this->getFormSubcomponent($component);

        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['form-createlocation'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($form);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($mapdiv);

        return $ret;
    }
}
