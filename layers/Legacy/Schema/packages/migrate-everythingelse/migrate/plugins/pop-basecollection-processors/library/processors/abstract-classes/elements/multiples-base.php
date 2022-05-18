<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_MultiplesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_MULTIPLE];
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($subComponentVariations = $this->getSubComponentVariations($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $subComponentVariations
            );
        }

        return $ret;
    }
}
