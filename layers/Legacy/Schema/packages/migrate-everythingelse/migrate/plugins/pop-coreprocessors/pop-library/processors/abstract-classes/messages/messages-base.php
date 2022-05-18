<?php

abstract class PoP_Module_Processor_MessagesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_MESSAGE];
    }

    public function getMessage(array $module)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        $ret['message'] = $this->getMessage($module);
        
        return $ret;
    }
}
