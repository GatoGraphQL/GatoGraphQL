<?php

abstract class PoP_Module_Processor_HideIfEmptyBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HIDEIFEMPTY];
    }
    
    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        
        $this->addJsmethod($ret, 'hideEmpty');

        return $ret;
    }
}
