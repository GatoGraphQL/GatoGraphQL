<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_CreateLocationFramesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AddLocations_TemplateResourceLoaderProcessor::class, PoP_AddLocations_TemplateResourceLoaderProcessor::RESOURCE_FRAME_CREATELOCATIONMAP];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'formMapLocationGeocode');
        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-map-locationgeocode');
        parent::initModelProps($component, $props);
    }

    public function getSubComponents(array $component): array
    {
        switch ($component[1]) {
            case self::MODULE_FRAME_CREATELOCATIONMAP:
                return array(
                    $this->getMapdivSubmodule($component),
                    $this->getFormSubmodule($component)
                );
        }

        return parent::getSubComponents($component);
    }

    public function getFormSubmodule(array $component)
    {
        return [GD_EM_Module_Processor_CreateLocationForms::class, GD_EM_Module_Processor_CreateLocationForms::MODULE_FORM_CREATELOCATION];
    }

    public function getMapdivSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $mapdiv = $this->getMapdivSubmodule($component);
        $form = $this->getFormSubmodule($component);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['form-createlocation'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($form);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($mapdiv);

        return $ret;
    }
}
