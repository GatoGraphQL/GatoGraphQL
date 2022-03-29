<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;

abstract class PoP_Module_Processor_MapStaticImageLocationsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE_LOCATIONS];
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        $urlparam = $this->getUrlparamSubmodule($module);
        return [
            new RelationalModuleField(
                'locations',
                [
                    $urlparam,
                ]
            ),
        ];
    }

    public function getUrlparamSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapStaticImageURLParams::class, PoP_Module_Processor_MapStaticImageURLParams::MODULE_MAP_STATICIMAGE_URLPARAM];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $urlparam = $this->getUrlparamSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['urlparam'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($urlparam);

        return $ret;
    }
}
