<?php

abstract class PoP_Module_Processor_HideIfEmptyBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HIDEIFEMPTY];
    }
    
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        
        $this->addJsmethod($ret, 'hideEmpty');

        return $ret;
    }
}
