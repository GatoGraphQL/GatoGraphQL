<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class GD_EM_Module_Processor_CreateLocationFramesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_AddLocations_TemplateResourceLoaderProcessor::class, PoP_AddLocations_TemplateResourceLoaderProcessor::RESOURCE_FRAME_CREATELOCATIONMAP];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'formMapLocationGeocode');
        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-map-locationgeocode');
        parent::initModelProps($componentVariation, $props);
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FRAME_CREATELOCATIONMAP:
                return array(
                    $this->getMapdivSubmodule($componentVariation),
                    $this->getFormSubmodule($componentVariation)
                );
        }

        return parent::getSubComponentVariations($componentVariation);
    }

    public function getFormSubmodule(array $componentVariation)
    {
        return [GD_EM_Module_Processor_CreateLocationForms::class, GD_EM_Module_Processor_CreateLocationForms::MODULE_FORM_CREATELOCATION];
    }

    public function getMapdivSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $mapdiv = $this->getMapdivSubmodule($componentVariation);
        $form = $this->getFormSubmodule($componentVariation);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['form-createlocation'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($form);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($mapdiv);

        return $ret;
    }
}
