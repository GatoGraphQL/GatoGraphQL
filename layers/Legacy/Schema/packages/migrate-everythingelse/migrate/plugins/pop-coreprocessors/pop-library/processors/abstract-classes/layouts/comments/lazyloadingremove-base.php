<?php

abstract class PoP_Module_Processor_LazyLoadingRemoveLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCRIPT_LAZYLOADINGREMOVE];
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['spinner'] = GD_CLASS_SPINNER;
        
        return $ret;
    }
}
