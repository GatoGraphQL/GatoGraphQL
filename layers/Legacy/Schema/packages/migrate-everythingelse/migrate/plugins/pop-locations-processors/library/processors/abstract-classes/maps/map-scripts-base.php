<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;

abstract class PoP_Module_Processor_MapScriptsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPT];
    }

    public function getCustomizationSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($script_customize = $this->getCustomizationSubmodule($module)) {
            $ret[] = $script_customize;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        return [
            new RelationalModuleField(
                'locations',
                [
                    [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::MODULE_MAP_SCRIPT_MARKERS],
                ]
            ),
        ];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($script_customize = $this->getCustomizationSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-customize'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($script_customize);
        }
        $markers = [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::MODULE_MAP_SCRIPT_MARKERS];
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-markers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($markers);

        return $ret;
    }
}
