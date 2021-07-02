<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_MultiplesBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_MULTIPLE];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($submodules = $this->getSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [ModuleUtils::class, 'getModuleOutputName'], 
                $submodules
            );
        }

        return $ret;
    }
}
