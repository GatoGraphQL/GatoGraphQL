<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_MapStaticImageLocationsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE_LOCATIONS];
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        $urlparam = $this->getUrlparamSubmodule($component);
        return [
            new RelationalModuleField(
                'locations',
                [
                    $urlparam,
                ]
            ),
        ];
    }

    public function getUrlparamSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapStaticImageURLParams::class, PoP_Module_Processor_MapStaticImageURLParams::COMPONENT_MAP_STATICIMAGE_URLPARAM];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $urlparam = $this->getUrlparamSubmodule($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['urlparam'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($urlparam);

        return $ret;
    }
}
