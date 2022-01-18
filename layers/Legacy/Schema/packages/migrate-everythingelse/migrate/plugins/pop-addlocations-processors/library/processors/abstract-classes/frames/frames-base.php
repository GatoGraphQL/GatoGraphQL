<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class GD_EM_Module_Processor_CreateLocationFramesBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AddLocations_TemplateResourceLoaderProcessor::class, PoP_AddLocations_TemplateResourceLoaderProcessor::RESOURCE_FRAME_CREATELOCATIONMAP];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'formMapLocationGeocode');
        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-map-locationgeocode');
        parent::initModelProps($module, $props);
    }

    public function getSubmodules(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_FRAME_CREATELOCATIONMAP:
                return array(
                    $this->getMapdivSubmodule($module),
                    $this->getFormSubmodule($module)
                );
        }

        return parent::getSubmodules($module);
    }

    public function getFormSubmodule(array $module)
    {
        return [GD_EM_Module_Processor_CreateLocationForms::class, GD_EM_Module_Processor_CreateLocationForms::MODULE_FORM_CREATELOCATION];
    }

    public function getMapdivSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapDivs::class, PoP_Module_Processor_MapDivs::MODULE_MAP_DIV];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $mapdiv = $this->getMapdivSubmodule($module);
        $form = $this->getFormSubmodule($module);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['form-createlocation'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($form);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-div'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($mapdiv);

        return $ret;
    }
}
