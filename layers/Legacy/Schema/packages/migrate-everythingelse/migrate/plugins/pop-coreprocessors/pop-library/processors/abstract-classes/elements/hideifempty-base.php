<?php

abstract class PoP_Module_Processor_HideIfEmptyBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HIDEIFEMPTY];
    }
    
    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        
        $this->addJsmethod($ret, 'hideEmpty');

        return $ret;
    }
}
